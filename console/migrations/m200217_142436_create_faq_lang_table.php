<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%faq_lang}}`.
 */
class m200217_142436_create_faq_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%faq_lang}}', [
            'id' => $this->primaryKey(),
            'faq_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull(),

            'question' => $this->string()->notNull(),
            'answer' => $this->text()->notNull(),
        ], 'ENGINE InnoDB');

        $this->createIndex('{{%idx-faq_lang-language}}', '{{%faq_lang}}', 'language');
        $this->createIndex('{{%idx-faq_lang-faq_id}}', '{{%faq_lang}}', 'faq_id');

        $this->addForeignKey('{{%fk-faq_lang-faq_id}}', '{{%faq_lang}}',
            'faq_id', '{{%faq}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%faq_lang}}');
    }
}
