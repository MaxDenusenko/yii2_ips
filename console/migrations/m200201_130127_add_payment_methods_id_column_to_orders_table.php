<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%orders}}`.
 */
class m200201_130127_add_payment_methods_id_column_to_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%orders}}', 'payment_method_id', $this->integer()->null());
        $this->createIndex('{{%idx-orders-payment_method_id}}', '{{%orders}}', 'payment_method_id');
        $this->addForeignKey('{{%fk-orders-payment_method_id}}', '{{%orders}}',
            'payment_method_id', '{{%payment_methods}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-orders-payment_method_id}}', '{{%orders}}');
        $this->dropIndex('{{%idx-orders-payment_method_id}}', '{{%orders}}');
        $this->dropColumn('{{%orders}}', 'payment_method_id');
    }
}
