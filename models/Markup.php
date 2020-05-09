<?php

namespace panix\mod\markup\models;

use panix\engine\CMS;
use Yii;
use panix\engine\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class Markup
 *
 * @property integer $id
 * @property string $name
 * @property string $sum
 * @property array $categories Category ids
 * @property array $manufacturers Manufacturer ids
 * @property array $suppliers Suppliers ids
 * @package panix\mod\markup\models
 *
 */
class Markup extends ActiveRecord
{

    const MODULE_ID = 'markup';

    /**
     * @var array ids of categories to apply discount
     */
    protected $_categories;

    /**
     * @var array ids of manufacturers to apply discount
     */
    protected $_manufacturers;

    /**
     * @var array ids of manufacturers to apply discount
     */
    protected $_suppliers;

    public static $categoryTable = '{{%markup__category}}';
    public static $manufacturerTable = '{{%markup__manufacturer}}';
    public static $supplierTable = '{{%markup__supplier}}';

    //public $useRules;


    public function attributeLabels()
    {
        return \yii\helpers\ArrayHelper::merge([
            'manufacturers' => self::t('MANUFACTURERS'),
            'userRoles' => self::t('USER_ROLES'),
        ], parent::attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%markup}}';
    }

    public static function find()
    {
        return new MarkupQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sum'], 'required'],
            ['switch', 'boolean'],
            ['name', 'string', 'max' => 255],
            ['sum', 'string', 'max' => 10],
            [['created_at', 'updated_at'], 'integer'],
            //[['discountManufacturers', 'discountCategories', 'userRoles'], 'each', 'rule' => ['integer']],
            [['manufacturers','categories','suppliers'], 'validateArray'],
            //[['manufacturers', 'categories'], 'default', 'value' => []],

            [['id', 'name', 'switch', 'sum'], 'safe'],
        ];
    }

    public function validateArray($attribute)
    {
        if (!is_array($this->{$attribute})) {
            $this->addError($attribute, 'The attribute must be array.');
        }
    }

    /**
     * @return array
     */
    public function getUserRoles()
    {
        return json_decode($this->roles);
    }

    /**
     * @param array $roles
     */
    public function setUserRoles(array $roles)
    {
        $this->roles = json_encode($roles);
    }


    /**
     * @param array $data
     */
    public function setCategories($data)
    {
        $this->_categories = $data;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        if (is_array($this->_categories))
            return $this->_categories;

        $this->_categories = self::getDb()->createCommand('SELECT category_id FROM '.self::$categoryTable.' WHERE markup_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();

        return $this->_categories;
    }

    /**
     * @param array $data
     */
    public function setManufacturers($data)
    {
        $this->_manufacturers = $data;
    }


    /**
     * @return array
     */
    public function getManufacturers()
    {
        if (is_array($this->_manufacturers))
            return $this->_manufacturers;

        $this->_manufacturers = self::getDb()->createCommand('SELECT manufacturer_id FROM '.self::$manufacturerTable.' WHERE markup_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();


        return $this->_manufacturers;
    }

    /**
     * @param array $data
     */
    public function setSuppliers($data)
    {
        $this->_suppliers = $data;
    }


    /**
     * @return array
     */
    public function getSuppliers()
    {
        if (is_array($this->_suppliers))
            return $this->_suppliers;

        $this->_suppliers = self::getDb()->createCommand('SELECT supplier_id FROM '.self::$supplierTable.' WHERE markup_id=:id')
            ->bindValue(':id', $this->id)
            ->queryColumn();


        return $this->_suppliers;
    }
    /**
     * Clear discount manufacturer and category
     */
    public function clearRelations()
    {
        self::getDb()->createCommand()
            ->delete(self::$manufacturerTable, 'markup_id=:id', [':id' => $this->id])
            ->execute();
        self::getDb()->createCommand()
            ->delete(self::$categoryTable, 'markup_id=:id', [':id' => $this->id])
            ->execute();
        self::getDb()->createCommand()
            ->delete(self::$supplierTable, 'markup_id=:id', [':id' => $this->id])
            ->execute();

    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->clearRelations();
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->clearRelations();

        // Process manufacturers
        if (!empty($this->_manufacturers)) {
            foreach ($this->_manufacturers as $id) {
                self::getDb()->createCommand()->insert(self::$manufacturerTable, [
                    'markup_id' => $this->id,
                    'manufacturer_id' => $id,
                ])->execute();
            }
        }

        // Process categories
        if (!empty($this->_categories)) {
            foreach (array_unique($this->_categories) as $id) {
                self::getDb()->createCommand()->insert(self::$categoryTable, [
                    'markup_id' => $this->id,
                    'category_id' => $id,
                ])->execute();
            }
        }
        // Process suppliers
        if (!empty($this->_suppliers)) {
            foreach (array_unique($this->_suppliers) as $id) {
                self::getDb()->createCommand()->insert(self::$supplierTable, [
                    'markup_id' => $this->id,
                    'supplier_id' => $id,
                ])->execute();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        $this->categories_data = json_encode(array_unique($this->categories));
        $this->manufacturers_data = json_encode(array_unique($this->manufacturers));
        $this->suppliers_data = json_encode(array_unique($this->suppliers));
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

}
