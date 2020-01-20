<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m200119_112518_add_ip_column_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'ip', $this->smallInteger()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'ip');
    }
}
