<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%category_tariffs}}`.
 */
class m200201_114412_add_description_column_to_category_tariffs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category_tariffs}}', 'description', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category_tariffs}}', 'description');
    }
}
