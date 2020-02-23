<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fragments}}`.
 */
class m200125_182206_create_fragments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fragments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fragments}}');
    }
}
