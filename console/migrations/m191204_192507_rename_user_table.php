<?php

use yii\db\Migration;

/**
 * Class m191204_192507_rename_user_table
 */
class m191204_192507_rename_user_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    public function down()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }
}
