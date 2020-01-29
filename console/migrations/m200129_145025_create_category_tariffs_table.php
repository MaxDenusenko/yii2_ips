<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_tariffs}}`.
 */
class m200129_145025_create_category_tariffs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_tariffs}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category_tariffs}}');
    }
}
