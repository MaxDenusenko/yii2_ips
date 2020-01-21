<?php

use yii2mod\rbac\migrations\Migration;

class m200120_222811_create_role_user extends Migration
{
    public function safeUp()
    {
        $this->createRole('user');
    }

    public function safeDown()
    {
        $this->removeRole('user');
    }
}
