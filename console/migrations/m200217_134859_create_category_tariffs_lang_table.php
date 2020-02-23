<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_tariffs_lang}}`.
 */
class m200217_134859_create_category_tariffs_lang_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_tariffs_lang}}', [
            'id' => $this->primaryKey(),
            'category_tariffs_id' => $this->integer()->notNull(),
            'language' => $this->string()->notNull(),

            'description' => $this->text(),
            'name' => $this->string()->notNull(),
        ], 'ENGINE InnoDB');

        $this->createIndex('{{%idx-category_tariffs_lang-language}}', '{{%category_tariffs_lang}}', 'language');
        $this->createIndex('{{%idx-category_tariffs_lang-category_tariffs_id}}', '{{%category_tariffs_lang}}', 'category_tariffs_id');

        $this->addForeignKey('{{%fk-category_tariffs_lang-category_tariffs_id}}', '{{%category_tariffs_lang}}',
            'category_tariffs_id', '{{%category_tariffs}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category_tariffs_lang}}');
    }
}
