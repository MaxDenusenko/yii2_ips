<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tariffs_assignment_lang}}`.
 */
class m200217_084137_create_tariffs_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tariffs_lang}}', [
            'id' => $this->primaryKey(),
            'tariffs_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull(),

            'description' => $this->text()->null(),
            'name' => $this->string()->notNull()
        ], 'ENGINE InnoDB');

        $this->createIndex('{{%idx-tariffs_lang-language}}', '{{%tariffs_lang}}', 'language');
        $this->createIndex('{{%idx-tariffs_lang-tariffs_id}}', '{{%tariffs_lang}}', 'tariffs_id');

        $this->addForeignKey('{{%fk-tariffs_lang-tariffs_id}}', '{{%tariffs_lang}}',
            'tariffs_id', '{{%tariffs}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tariffs_lang}}');
    }
}
