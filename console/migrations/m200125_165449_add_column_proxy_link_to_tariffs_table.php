<?php

use yii\db\Migration;

/**
 * Class m200125_165449_add_column_proxy_link_to_tariffs_table
 */
class m200125_165449_add_column_proxy_link_to_tariffs_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariffs}}', 'proxy_link', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%tariffs}}', 'proxy_link');
    }

}
