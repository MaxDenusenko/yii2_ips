<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_lang}}`.
 */
class m200217_191827_create_news_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_lang}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull(),

            'title' => $this->string()->notNull(),
            'body' => $this->text()->notNull(),
        ], 'ENGINE InnoDB');

        $this->createIndex('{{%idx-news_lang-language}}', '{{%news_lang}}', 'language');
        $this->createIndex('{{%idx-news_lang-news_id}}', '{{%news_lang}}', 'news_id');

        $this->addForeignKey('{{%fk-news_lang-faq_id}}', '{{%news_lang}}',
            'news_id', '{{%news}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_lang}}');
    }
}
