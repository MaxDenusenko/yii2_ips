<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m200201_115431_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),

            'comment' => $this->string()->null(),
            'amount' => $this->float()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created' => $this->integer()->notNull(),
            'updated' => $this->integer()->notNull(),
        ]);

        $this->createIndex('{{%idx-orders-user_id}}', '{{%orders}}', 'user_id');
        $this->addForeignKey('{{%fk-orders-user_id}}', '{{%orders}}',
            'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }
}
