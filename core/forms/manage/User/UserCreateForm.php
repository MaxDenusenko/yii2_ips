<?php


namespace core\forms\manage\User;


use core\entities\User\User;
use yii\base\Model;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $telegram;
    public $gabber;

    public function rules(): array
    {
        return [
            [['username', 'email'] , 'required'],
            ['email', 'email'],
            [['username', 'email', 'full_name', 'telegram', 'gabber'] , 'string', 'max' => 255],
            [['username', 'email'] , 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6]
        ];
    }
}
