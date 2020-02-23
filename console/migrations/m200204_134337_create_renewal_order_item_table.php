<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%renewal_order_item}}`.
 */
class m200204_134337_create_renewal_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%renewal_order_items}}', [
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
        ]);

        $this->createIndex('{{%idx-renewal_order_items-order_id}}', '{{%renewal_order_items}}', 'order_id');
        $this->addForeignKey('{{%fk-renewal_order_items-order_id}}', '{{%renewal_order_items}}',
            'order_id', '{{%orders}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-renewal_order_items-product_id}}', '{{%renewal_order_items}}', 'product_id');
        $this->addForeignKey('{{%fk-renewal_order_items-product_id}}', '{{%renewal_order_items}}',
            'product_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-renewal_order_items-product_user}}', '{{%renewal_order_items}}', 'product_user');
        $this->addForeignKey('{{%fk-renewal_order_items-product_user}}', '{{%renewal_order_items}}',
            'product_user', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-renewal_order_items-product_hash}}', '{{%renewal_order_items}}', 'product_hash');
        $this->addForeignKey('{{%fk-renewal_order_items-product_hash}}', '{{%renewal_order_items}}',
            'product_hash', '{{%tariff_assignments}}', 'hash_id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%renewal_order_items}}');
    }
}
