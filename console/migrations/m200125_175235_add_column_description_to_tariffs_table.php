<?php

use yii\db\Migration;

/**
 * Class m200125_175235_add_column_description_to_tariffs_table
 */
class m200125_175235_add_column_description_to_tariffs_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariffs}}', 'description', $this->text());
    }

    public function down()
    {
        $this->dropColumn('{{%tariffs}}', 'description');
    }
}
