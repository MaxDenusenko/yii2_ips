<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m200131_125907_create_currencies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currencies}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'active' => $this->smallInteger()->defaultValue(0),
            'base' => $this->smallInteger()->defaultValue(0),
            'symbol' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currencies}}');
    }
}
