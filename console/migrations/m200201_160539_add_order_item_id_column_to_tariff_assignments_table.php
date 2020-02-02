<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tariff_assignments}}`.
 */
class m200201_160539_add_order_item_id_column_to_tariff_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tariff_assignments}}', 'order_item_id', $this->integer()->null());
        $this->createIndex('{{%idx-tariff_assignments-order_item_id}}', '{{%tariff_assignments}}', 'order_item_id');
        $this->addForeignKey('{{%fk-tariff_assignments-order_item_id}}', '{{%tariff_assignments}}',
            'order_item_id', '{{%order_items}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-tariff_assignments-order_item_id}}', '{{%tariff_assignments}}');
        $this->dropIndex('{{%idx-tariff_assignments-order_item_id}}', '{{%tariff_assignments}}');
        $this->dropColumn('{{%tariff_assignments}}', 'order_item_id');
    }
}
