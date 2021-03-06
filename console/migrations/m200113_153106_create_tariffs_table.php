<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariffs}}`.
 */
class m200113_153106_create_tariffs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%tariffs}}', [
            'id' => $this->primaryKey(),
            'number' => $this->integer()->notNull(),
            'price' => $this->float()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-tariffs-number}}', '{{%tariffs}}', 'number', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tariffs}}');
    }
}
