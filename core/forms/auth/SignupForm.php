<?php
namespace core\forms\auth;

use Yii;
use yii\base\Model;
use core\entities\User\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $telegram;
    public $gabber;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\core\entities\User\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email', 'full_name', 'telegram', 'gabber'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\core\entities\User\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'full_name' => 'ФИО',
            'telegram' => 'Telegram',
            'gabber' => 'Gabber',
        ];
    }
}
