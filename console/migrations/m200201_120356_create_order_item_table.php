<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m200201_120356_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->float()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'cost' => $this->float()->notNull(),
        ]);

        $this->createIndex('{{%idx-order_items-order_id}}', '{{%order_items}}', 'order_id');
        $this->addForeignKey('{{%fk-order_items-order_id}}', '{{%order_items}}',
            'order_id', '{{%orders}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-order_items-product_id}}', '{{%order_items}}', 'product_id');
        $this->addForeignKey('{{%fk-order_items-product_id}}', '{{%order_items}}',
            'product_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_items}}');
    }
}
