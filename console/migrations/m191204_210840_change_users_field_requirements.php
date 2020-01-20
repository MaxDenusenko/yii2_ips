<?php

use yii\db\Migration;

/**
 * Class m191204_210840_change_users_field_requirements
 */
class m191204_210840_change_users_field_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%users}}', 'username', $this->string());
        $this->alterColumn('{{%users}}', 'password_hash', $this->string());
        $this->alterColumn('{{%users}}', 'email', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%users}}', 'username', $this->string()->notNull());
        $this->alterColumn('{{%users}}', 'password_hash', $this->string()->notNull());
        $this->alterColumn('{{%users}}', 'email', $this->string()->notNull());
    }

}
