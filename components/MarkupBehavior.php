<?php

namespace panix\mod\markup\components;

use Yii;
use yii\db\ActiveRecord;
use panix\mod\markup\models\Markup;
use yii\base\Behavior;

/**
 * Class MarkupBehavior
 *
 * @property mixed $hasDiscount
 * @property mixed $originalPrice
 * @property mixed $discountPrice
 * @property mixed $discountEndDate
 * @property mixed $discountSum
 * @property mixed $discountSumNum
 *
 * @package panix\mod\markup\components
 */
class MarkupBehavior extends Behavior
{

    /**
     * @var mixed|null|Discount
     */
    public $hasDiscount = null;

    /**
     * @var float product price before discount applied
     */
    public $originalPrice;
    public $discountPrice;
    public $discountSum;
    public $discountSumNum;
    public $discountEndDate;

    /**
     * @var null
     */
    private $discounts = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    /**
     * After find event
     */
    public function afterFind()
    {
        /** @var \panix\mod\shop\models\Product $owner */
        $owner = $this->owner;
        if (!$owner->isNewRecord) {
            if ($this->discounts === null) {

                /*$this->discounts = Discount::find()
                    ->published()
                    ->applyDate()
                    ->all();*/
                $this->discounts = Yii::$app->getModule('markup')->discounts;
            }
        }

        if ($this->hasDiscount !== null)
            return;

        $user = Yii::$app->user;
        if (Yii::$app instanceof \yii\console\Application) {
            $user = null;
        }
        // Personal product discount
        if (!empty($owner->discount)) {
            $discount = new Markup();
            $discount->name = Yii::t('app/default', 'Скидка');
            $discount->sum = $owner->discount;
            $this->applyDiscount($discount);
        }

        // Process discount rules
        if (!$this->hasDiscount()) {

            foreach ($this->discounts as $discount) {

                $apply = false;

                // Validate category
                if ($this->searchArray($discount->categories, array_values($this->ownerCategories))) {
                    $apply = true;
                    // Validate manufacturer


                    if (!empty($discount->manufacturers)) {
                        $apply = in_array($owner->manufacturer_id, $discount->manufacturers);
                    }

                    if (Yii::$app->user->can('Admin') !== true) {
                        //$apply = false;
                    }
                }


                // Apply discount by user role. Discount for admin disabled.
                /*if (!empty($discount->userRoles)) {
                    //if (!empty($discount->userRoles) && $user->checkAccess('Admin') !== true) {
                    $apply = false;

                    foreach ($discount->userRoles as $role) {
                        if ($user->checkAccess($role)) {
                            $apply = true;
                            break;
                        }
                    }
                }*/


                if ($apply === true) {
                    $this->applyDiscount($discount);
                }
            }
        }

        // Personal discount for users.
        if (!$user->isGuest && !empty($user->discount) && !$this->hasDiscount()) {
            $discount = new Markup();
            $discount->name = Yii::t('app/default', 'Персональная скидка');
            $discount->sum = $user->discount;
            $this->applyDiscount($discount);
        }
    }

    /**
     * Apply discount to product and decrease its price
     * @param Markup $markup
     */
    protected function applyDiscount(Markup $markup)
    {
        /** @var \panix\mod\shop\models\Product $owner */
        $owner = $this->owner;
        if ($this->hasDiscount === null) {

            $sum = $markup->sum;
            $this->discountSumNum = $sum;
            if ('%' === substr($markup->sum, -1, 1)) {
                $this->discountSumNum = ((double) $sum) / 100;
                $sum = $owner->price * $this->discountSumNum;

            }
            $this->originalPrice = $owner->price;
            $this->discountPrice = $owner->price - $sum;
            $this->discountEndDate = $markup->end_date;
            $this->discountSum = $markup->sum;
            $this->hasDiscount = $markup;

        }

    }

    /**
     * Search value from $a in $b
     * @param array $a
     * @param array $b
     * @return bool
     */
    protected function searchArray(array $a, array $b)
    {
        foreach ($a as $v)
            if (in_array($v, $b))
                return true;
        return false;
    }

    /**
     * @return array
     */
    public function getOwnerCategories()
    {
        // $id = 'discount_product_categories' . $this->owner->date_update;
        //$data = Yii::$app->cache->get($id);


        //if ($data === false) {
        $data = \yii\helpers\ArrayHelper::map($this->owner->categories, 'id', 'id');
        //$data = $this->owner->categories;
        //Yii::$app->cache->set($id, $data);
        // }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return !($this->hasDiscount === null);
    }

}