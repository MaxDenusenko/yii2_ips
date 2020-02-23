<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%coupons}}`.
 */
class m200216_101304_create_coupons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%coupons}}', [
            'id' => $this->primaryKey(),
            'number' => $this->integer()->null(),
            'code' => $this->string()->notNull(),
            'per_cent' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ]);

        $this->createIndex('{{%idx-coupons-code}}', '{{%coupons}}', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%coupons}}');
    }
}
