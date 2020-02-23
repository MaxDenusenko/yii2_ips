<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%coupon_uses}}`.
 */
class m200216_111409_create_coupon_uses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%coupon_uses}}', [
            'id' => $this->primaryKey(),
            'date_use' => $this->dateTime()->notNull(),
            'coupon_id' => $this->integer()->notNull(),
            'tariff_assignment_hash_id' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'sum' => $this->float()->null(),
        ]);

        $this->createIndex('{{%idx-coupon_uses-coupon_id}}', '{{%coupon_uses}}', 'coupon_id');
        $this->addForeignKey('{{%fk-coupon_uses-coupon_id}}', '{{%coupon_uses}}',
            'coupon_id', '{{%coupons}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-coupon_uses-user_id}}', '{{%coupon_uses}}', 'user_id');
        $this->addForeignKey('{{%fk-coupon_uses-user_id}}', '{{%coupon_uses}}',
            'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('{{%idx-coupon_uses-tariff_assignment_hash_id}}', '{{%coupon_uses}}', 'tariff_assignment_hash_id');
        $this->addForeignKey('{{%fk-coupon_uses-tariff_assignment_hash_id}}', '{{%coupon_uses}}',
            'tariff_assignment_hash_id', '{{%tariff_assignments}}', 'hash_id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%coupon_uses}}');
    }
}
