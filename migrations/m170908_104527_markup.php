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


        $this->createIndex('switch', Markup::tableName(), 'switch');
    }

    public function down()
    {
        $this->dropTable(Markup::tableName());
    }

}
