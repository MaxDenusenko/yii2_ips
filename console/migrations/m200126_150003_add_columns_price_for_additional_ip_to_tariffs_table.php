<?php

use yii\db\Migration;

/**
 * Class m200126_150003_add_columns_price_for_additional_ip_to_tariffs_table
 */
class m200126_150003_add_columns_price_for_additional_ip_to_tariffs_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariffs}}', 'price_for_additional_ip', $this->integer()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%tariffs}}', 'price_for_additional_ip');
    }
}
