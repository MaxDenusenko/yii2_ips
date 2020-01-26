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
    public $tariff_reminder;

    public function rules(): array
    {
        return [
            [['username', 'email'] , 'required'],
            ['email', 'email'],
            [['username', 'email', 'full_name', 'telegram', 'gabber'] , 'string', 'max' => 255],
            [['username', 'email'] , 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
            [['tariff_reminder'] , 'integer'],
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
            'full_name' => 'ФИО',
            'telegram' => 'Telegram',
            'gabber' => 'Jabber',
            'tariff_reminder' => 'Напоминание о окончании тарифа (за n дней)',
        ];
    }
}
