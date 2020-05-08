<?php

namespace panix\mod\markup\migrations;

/**
 * Generation migrate by PIXELION CMS
 *
 * @author PIXELION CMS development team <dev@pixelion.com.ua>
 * @link http://pixelion.com.ua PIXELION CMS
 *
 * Class m170908_104527_markup
 */

use panix\engine\db\Migration;
use panix\mod\markup\models\Markup;

class m170908_104527_markup extends Migration
{

    public static $categoryTable = '{{%markup__category}}';
    public static $manufacturerTable = '{{%markup__manufacturer}}';

    public function up()
    {

        $this->createTable(Markup::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'start_date' => $this->integer(11)->null(),
            'end_date' => $this->integer(11)->null(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);


        $this->createTable(self::$categoryTable, [
            'id' => $this->primaryKey()->unsigned(),
            'markup_id' => $this->integer()->unsigned(),
            'category_id' => $this->integer()->unsigned(),
        ], $this->tableOptions);


        $this->createTable(self::$manufacturerTable, [
            'id' => $this->primaryKey()->unsigned(),
            'markup_id' => $this->integer()->unsigned(),
            'manufacturer_id' => $this->integer()->unsigned(),
        ], $this->tableOptions);


        $this->createIndex('switch', Markup::tableName(), 'switch');
        $this->createIndex('start_date', Markup::tableName(), 'start_date');
        $this->createIndex('end_date', Markup::tableName(), 'end_date');

        $this->createIndex('markup_id', self::$categoryTable, 'markup_id');
        $this->createIndex('category_id', self::$categoryTable, 'category_id');

        $this->createIndex('markup_id', self::$manufacturerTable, 'markup_id');
        $this->createIndex('manufacturer_id', self::$manufacturerTable, 'manufacturer_id');
    }

    public function down()
    {
        $this->dropTable(Markup::tableName());
        $this->dropTable(self::$categoryTable);
        $this->dropTable(self::$manufacturerTable);
    }

}
