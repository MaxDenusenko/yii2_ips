<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%coin_pay}}`.
 */
class m200201_170133_create_coin_pay_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%coin_pay}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'pay_link' => $this->string()->notNull(),
            'identity' => $this->string()->notNull(),
            'status' => $this->string()->null(),
            'charge_id' => $this->string()->null(),
        ]);

        $this->createIndex('{{%idx-coin_pay-order_id}}', '{{%coin_pay}}', 'order_id');
        $this->addForeignKey('{{%fk-coin_pay-order_id}}', '{{%coin_pay}}',
            'order_id', '{{%orders}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%coin_pay}}');
    }
}
