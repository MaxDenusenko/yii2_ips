<?php

use yii\db\Migration;

/**
 * Class m200126_143817_add_columns_discount_to_tariff_assignments_table
 */
class m200126_143817_add_columns_discount_to_tariff_assignments_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariff_assignments}}', 'discount', $this->integer()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%tariff_assignments}}', 'discount');
    }
}
