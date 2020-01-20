<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menus}}`.
 */
class m200110_115835_create_menus_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_menus}}', [
            'id' => $this->primaryKey(),
            'tree'  => $this->integer()->null(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'url' => $this->string()->null(),
            'font_awesome_icon_class' => $this->string()->null(),
            'menu_depth' => $this->integer()->null(),
        ], $tableOptions);

        $this->createIndex('lft', '{{%shop_menus}}', ['tree', 'lft', 'rgt']);
        $this->createIndex('rgt', '{{%shop_menus}}', ['tree', 'rgt']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_menus}}');
    }
}
