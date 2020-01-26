<?php

use yii\db\Migration;

/**
 * Class m200126_134820_add_column_tariff_reminder_to_users_table
 */
class m200126_134820_add_column_tariff_reminder_to_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'tariff_reminder', $this->smallInteger()->null()->defaultValue(3));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'tariff_reminder');
    }
}
