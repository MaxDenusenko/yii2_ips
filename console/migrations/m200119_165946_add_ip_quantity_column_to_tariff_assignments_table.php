<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tariff_assignments}}`.
 */
class m200119_165946_add_ip_quantity_column_to_tariff_assignments_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariff_assignments}}', 'ip_quantity', $this->smallInteger()->null()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('{{%tariff_assignments}}', 'ip_quantity');
    }
}
