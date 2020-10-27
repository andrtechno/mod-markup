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
 * @property mixed $discountSumNum
 *
 * @package panix\mod\markup\components
 */
class MarkupBehavior extends Behavior
{

    /**
     * @var mixed|null|Markup
     */
    public $hasMarkup = null;

    /**
     * @var float product price before markup applied
     */
    public $markupPrice;

    /**
     * @var null
     */
    private $markups = null;

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
            if ($this->markups === null) {
                $this->markups = Yii::$app->getModule('markup')->markups;
            }
        }

        if ($this->hasMarkup !== null)
            return;

        // Process discount rules
        if (!$this->hasMarkup()) {
            foreach ($this->markups as $markup) {
                $apply = false;

                // Validate category
                if ($this->searchArray($markup->categories, array_values($this->ownerCategories))) {

                    $apply = true;
                }
                // Validate manufacturer
                if (!empty($markup->manufacturers)) {
                    if (in_array($owner->manufacturer_id, $markup->manufacturers)) {
                        $apply = true;
                    }
                }

                // Validate manufacturer
                if (!empty($markup->suppliers)) {
                    if (in_array($owner->supplier_id, $markup->suppliers)) {
                        $apply = true;
                    }
                }

                if ($apply === true) {
                    $this->applyMarkup($markup);
                }
            }
        }
    }

    /**
     * Apply markup to product and decrease its price
     * @param Markup $markup
     */
    protected function applyMarkup(Markup $markup)
    {
        /** @var \panix\mod\shop\models\Product $owner */
        $owner = $this->owner;
        if ($this->hasMarkup === null) {
            if ($owner->price_purchase) {
                $sum = $markup->sum;
                if ('%' === substr($markup->sum, -1, 1)) {
                    $sum = $owner->price_purchase * ((double)$sum) / 100;

                }
                // $this->originalPrice = $owner->price_purchase;
                $owner->price = $owner->price_purchase + $sum;
                $this->hasMarkup = $markup;
            }

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
        $id = 'markup_product_categories';
        $data = Yii::$app->cache->get($id);


        if ($data === false) {
            $data = \yii\helpers\ArrayHelper::map($this->owner->categories, 'id', 'id');
            //  $data = $this->owner->categories;
            Yii::$app->cache->set($id, $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasMarkup()
    {
        return !($this->hasMarkup === null);
    }

}
