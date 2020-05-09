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

    public function up()
    {

        $this->createTable(Markup::tableName(), [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'sum' => $this->string(10)->notNull(),
            'roles' => $this->string(255),
            'switch' => $this->boolean()->defaultValue(1),
            'manufacturers_data' => $this->text()->null(),
            'categories_data' => $this->text()->null(),
            'suppliers_data' => $this->text()->null(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $this->tableOptions);


        $this->createTable(Markup::$categoryTable, [
            'id' => $this->primaryKey()->unsigned(),
            'markup_id' => $this->integer()->unsigned(),
            'category_id' => $this->integer()->unsigned(),
        ], $this->tableOptions);


        $this->createTable(Markup::$manufacturerTable, [
            'id' => $this->primaryKey()->unsigned(),
            'markup_id' => $this->integer()->unsigned(),
            'manufacturer_id' => $this->integer()->unsigned(),
        ], $this->tableOptions);


        $this->createTable(Markup::$supplierTable, [
            'id' => $this->primaryKey()->unsigned(),
            'markup_id' => $this->integer()->unsigned(),
            'supplier_id' => $this->integer()->unsigned(),
        ], $this->tableOptions);


        $this->createIndex('switch', Markup::tableName(), 'switch');

        $this->createIndex('markup_id', Markup::$categoryTable, 'markup_id');
        $this->createIndex('category_id', Markup::$categoryTable, 'category_id');

        $this->createIndex('markup_id', Markup::$manufacturerTable, 'markup_id');
        $this->createIndex('manufacturer_id', Markup::$manufacturerTable, 'manufacturer_id');

        $this->createIndex('markup_id', Markup::$supplierTable, 'markup_id');
        $this->createIndex('supplier_id', Markup::$supplierTable, 'supplier_id');
    }

    public function down()
    {
        $this->dropTable(Markup::tableName());
        $this->dropTable(Markup::$categoryTable);
        $this->dropTable(Markup::$manufacturerTable);
        $this->dropTable(Markup::$supplierTable);
    }

}
