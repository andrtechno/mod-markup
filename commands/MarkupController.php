<?php

namespace panix\mod\markup\commands;

use Yii;
use panix\engine\console\controllers\ConsoleController;
use panix\mod\shop\models\Product;
use panix\mod\markup\models\Markup;

class MarkupController extends ConsoleController
{

    public function actionIndex()
    {
        $discounts = Yii::$app->getModule('markup')->discounts;
        foreach ($discounts as $discount) {
            $apply = false;

            // Validate category
            //if ($this->searchArray($discount->discountCategories, array_values($this->ownerCategories))) {
                $apply = true;
           // }
print_r($discount->categories);
print_r($discount->manufacturers);
            // Validate manufacturer
            //if (!empty($discount->discountManufacturers)) {
            //    $apply = in_array($this->owner->manufacturer_id, $discount->discountManufacturers);
            //}



            //if ($apply === true) {
            //    $this->applyDiscount($discount);
           // }
        }
        die;
        $tableName = Product::tableName();
        $products = Product::getDb()->createCommand("SELECT * FROM {$tableName}")->queryAll();

        $ids = [];
        foreach ($products as $key => $value) {
            $ids[] = $value['id'];
        }

        Product::updateAll(['has_discount' => 1], ['id' => $ids]);
    }


    protected function applyDiscount(Markup $discount)
    {

        if ($this->hasDiscount === null) {

            $sum = $discount->sum;
            if ('%' === substr($discount->sum, -1, 1)) {
                $sum = $this->owner->price * (int)$sum / 100;
            }
            $this->originalPrice = $this->owner->price;
            $this->discountPrice = $this->owner->price - $sum;
            $this->discountSum = $discount->sum;
            $this->discountSumNum = $sum;
            $this->hasDiscount = $discount;

        }

    }
    protected function searchArray(array $a, array $b)
    {
        foreach ($a as $v)
            if (in_array($v, $b))
                return true;
        return false;
    }

}
