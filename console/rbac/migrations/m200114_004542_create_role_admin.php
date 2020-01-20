<?php

use yii2mod\rbac\migrations\Migration;

class m200114_004542_create_role_admin extends Migration
{
    public function safeUp()
    {
        $this->createRole('admin', 'admin has all available permissions.');
    }

    public function safeDown()
    {
        $this->removeRole('admin');
    }
}
