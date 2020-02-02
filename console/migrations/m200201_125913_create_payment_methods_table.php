<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_methods}}`.
 */
class m200201_125913_create_payment_methods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_methods}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_methods}}');
    }
}
