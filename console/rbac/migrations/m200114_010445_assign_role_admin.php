<?php

use yii2mod\rbac\migrations\Migration;

class m200114_010445_assign_role_admin extends Migration
{
    public function safeUp()
    {
        $this->assign('admin', 1);
    }

    public function safeDown(){}
}
