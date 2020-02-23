<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%payment_methods}}`.
 */
class m200211_093800_add_label_column_to_payment_methods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%payment_methods}}', 'label', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%payment_methods}}', 'label');
    }
}
