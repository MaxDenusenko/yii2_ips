<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tariff_defaults}}`.
 */
class m200125_115525_add_columns_to_tariff_defaults_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tariff_defaults}}', 'file_path', $this->string());
        $this->addColumn('{{%tariff_defaults}}', 'ip_quantity', $this->smallInteger()->null()->defaultValue(1));
        $this->addColumn('{{%tariff_defaults}}', 'type', $this->smallInteger()->null()->defaultValue(1));
        $this->addColumn('{{%tariff_defaults}}', 'extend_days', $this->smallInteger()->null());
        $this->addColumn('{{%tariff_defaults}}', 'extend_hours', $this->smallInteger()->null());
        $this->addColumn('{{%tariff_defaults}}', 'extend_minutes', $this->smallInteger()->null());

        $this->dropColumn('{{%tariff_defaults}}', 'name');

        $this->addColumn('{{%tariff_defaults}}', 'tariff_id', $this->integer()->notNull());
        $this->createIndex('{{%idx-tariff_defaults-tariff_id}}', '{{%tariff_defaults}}', 'tariff_id');
        $this->addForeignKey('{{%fk-tariff_defaults-tariff_id}}', '{{%tariff_defaults}}',
            'tariff_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tariff_defaults}}', 'file_path');
        $this->dropColumn('{{%tariff_defaults}}', 'ip_quantity');
        $this->dropColumn('{{%tariff_defaults}}', 'type');
        $this->dropColumn('{{%tariff_defaults}}', 'extend_days');
        $this->dropColumn('{{%tariff_defaults}}', 'extend_hours');
        $this->dropColumn('{{%tariff_defaults}}', 'extend_minutes');

        $this->addColumn('{{%tariff_defaults}}', 'name', $this->string()->notNull());

        $this->dropForeignKey('{{%fk-tariff_defaults-tariff_id}}', '{{%tariff_defaults}}');
        $this->dropIndex('{{%idx-tariff_defaults-tariff_id}}', '{{%tariff_defaults}}');
        $this->dropColumn('{{%tariff_defaults}}', 'tariff_id');
    }
}
