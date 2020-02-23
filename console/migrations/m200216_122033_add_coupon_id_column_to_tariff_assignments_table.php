<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tariff_assignments}}`.
 */
class m200216_122033_add_coupon_id_column_to_tariff_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tariff_assignments}}', 'coupon_id', $this->integer()->null());
        $this->createIndex('{{%idx-tariff_assignments-coupon_id}}', '{{%tariff_assignments}}', 'coupon_id');
        $this->addForeignKey('{{%fk-tariff_assignments-coupon_id}}', '{{%tariff_assignments}}',
            'coupon_id', '{{%coupons}}', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-tariff_assignments-coupon_id}}', '{{%tariff_assignments}}');
        $this->dropIndex('{{%idx-tariff_assignments-coupon_id}}', '{{%tariff_assignments}}');
        $this->dropColumn('{{%tariff_assignments}}', 'coupon_id');
    }
}
