<?php

use yii\db\Migration;

/**
 * Class m200129_150347_add_column_category_id_to_tariffs_table
 */
class m200129_150347_add_column_category_id_to_tariffs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tariffs}}', 'category_id', $this->integer()->null());
        $this->createIndex('{{%idx-tariffs-category_id}}', '{{%tariffs}}', 'category_id');
        $this->addForeignKey('{{%fk-tariffs-category_id}}', '{{%tariffs}}',
            'category_id', '{{%category_tariffs}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-tariffs-category_id}}', '{{%tariffs}}');
        $this->dropIndex('{{%idx-tariffs-category_id}}', '{{%tariffs}}');
        $this->dropColumn('{{%tariffs}}', 'category_id');
    }

}
