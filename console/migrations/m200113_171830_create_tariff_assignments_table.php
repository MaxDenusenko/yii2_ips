<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariff_assignments}}`.
 */
class m200113_171830_create_tariff_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%tariff_assignments}}', [
            'tariff_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'hash_id' => $this->string()->notNull(),

            'file_path' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),

            'IPs' => $this->string(),
            'mb_limit' => $this->integer(),
            'quantity_incoming_traffic' => $this->string(),
            'quantity_outgoing_traffic' => $this->string(),
            'date_to' => $this->string(),
            'time_to' => $this->string(),

        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-tariff_assignments}}', '{{%tariff_assignments}}', ['tariff_id', 'user_id', 'hash_id']);

        $this->createIndex('{{%idx-tariff_assignments-tariff_id}}', '{{%tariff_assignments}}', 'tariff_id');
        $this->createIndex('{{%idx-tariff_assignments-user_id}}', '{{%tariff_assignments}}', 'user_id');
        $this->createIndex('{{%idx-tariff_assignments-hash_id}}', '{{%tariff_assignments}}', 'hash_id');

        $this->addForeignKey('{{%fk-tariff_assignments-tariff_id}}', '{{%tariff_assignments}}', 'tariff_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-tariff_assignments-user_id}}', '{{%tariff_assignments}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tariff_assignments}}');
    }
}
