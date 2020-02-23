<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tariff_assignments}}`.
 */
class m200215_123220_add_time_left_and_can_pause_column_to_tariff_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tariff_assignments}}', 'time_left', $this->integer()->null());
        $this->addColumn('{{%tariff_assignments}}', 'can_pause', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tariff_assignments}}', 'time_left');
        $this->dropColumn('{{%tariff_assignments}}', 'can_pause');
    }
}
