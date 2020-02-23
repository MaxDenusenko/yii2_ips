<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariff_defaults}}`.
 */
class m200119_131900_create_tariff_defaults_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tariff_defaults}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'mb_limit' => $this->integer(),
            'quantity_incoming_traffic' => $this->string(),
            'quantity_outgoing_traffic' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tariff_defaults}}');
    }
}
