<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%category_tariffs}}`.
 */
class m200217_151805_add_nested_sets_columns_to_category_tariffs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category_tariffs}}', 'slug', $this->string()->null());
        $this->addColumn('{{%category_tariffs}}', 'lft', $this->integer()->notNull());
        $this->addColumn('{{%category_tariffs}}', 'rgt', $this->integer()->notNull());
        $this->addColumn('{{%category_tariffs}}', 'depth', $this->integer()->notNull());

        $this->createIndex('{{%idx-category_tariffs-slug}}', '{{%category_tariffs}}', 'slug', true);

        $this->insert('{{%category_tariffs}}', [
            'id' => 1,
            'slug' => 'root',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-category_tariffs-slug}}', '{{%category_tariffs}}');
        $this->dropColumn('{{%category_tariffs}}', 'slug');
        $this->dropColumn('{{%category_tariffs}}', 'lft');
        $this->dropColumn('{{%category_tariffs}}', 'rgt');
        $this->dropColumn('{{%category_tariffs}}', 'depth');
    }
}
