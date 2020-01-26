<?php

use yii\db\Migration;

/**
 * Class m200126_141314_add_column_hash_to_tariff_assignments_table
 */
class m200126_141314_add_column_hash_to_tariff_assignments_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%tariff_assignments}}', 'hash', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%tariff_assignments}}', 'hash');
    }
}
