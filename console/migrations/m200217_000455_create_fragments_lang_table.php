<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fragments_lang}}`.
 */
class m200217_000455_create_fragments_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fragments_lang}}', [
            'id' => $this->primaryKey(),
            'fragment_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull(),

            'text' => $this->text(),
        ], 'ENGINE InnoDB');

        $this->createIndex('{{%idx-fragments_lang-language}}', '{{%fragments_lang}}', 'language');
        $this->createIndex('{{%idx-fragments_lang-fragment_id}}', '{{%fragments_lang}}', 'fragment_id');

        $this->addForeignKey('{{%fk-fragments_lang-fragment_id}}', '{{%fragments_lang}}',
            'fragment_id', '{{%fragments}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fragments_lang}}');
    }
}
