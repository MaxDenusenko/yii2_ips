<?php

use yii2mod\rbac\migrations\Migration;

class m200120_224534_add_admin_child extends Migration
{
    public function safeUp()
    {
        $this->addChild('admin', 'user');
    }

    public function safeDown()
    {
        $this->removeChild('admin', 'user');
    }
}
