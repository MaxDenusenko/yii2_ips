<?php

use yii\db\Migration;

/**
 * Class m200126_202847_add_column_qty_proxy_to_tariffs_table
 */
class m200126_202847_add_column_qty_proxy_to_tariffs_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariffs}}', 'qty_proxy', $this->string()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%tariffs}}', 'qty_proxy');
    }
}
