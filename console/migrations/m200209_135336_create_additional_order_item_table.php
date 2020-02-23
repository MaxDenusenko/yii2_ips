<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%additional_order_item}}`.
 */
class m200209_135336_create_additional_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%additional_order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'product_user' => $this->integer()->notNull(),
            'product_hash' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->float()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'cost' => $this->float()->notNull(),
            'currency' => $this->string()->notNull(),
            'additional_ip' => $this->integer()->defaultValue(1)->notNull(),
        ]);

        $this->createIndex('{{%idx-additional_order_item-order_id}}', '{{%additional_order_item}}', 'order_id');
        $this->addForeignKey('{{%fk-additional_order_item-order_id}}', '{{%additional_order_item}}',
            'order_id', '{{%orders}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-additional_order_item-product_id}}', '{{%additional_order_item}}', 'product_id');
        $this->addForeignKey('{{%fk-additional_order_item-product_id}}', '{{%additional_order_item}}',
            'product_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-additional_order_item-product_user}}', '{{%additional_order_item}}', 'product_user');
        $this->addForeignKey('{{%fk-additional_order_item-product_user}}', '{{%additional_order_item}}',
            'product_user', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-additional_order_item-product_hash}}', '{{%additional_order_item}}', 'product_hash');
        $this->addForeignKey('{{%fk-additional_order_item-product_hash}}', '{{%additional_order_item}}',
            'product_hash', '{{%tariff_assignments}}', 'hash_id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%additional_order_item}}');
    }
}
