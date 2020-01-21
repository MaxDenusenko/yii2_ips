<?php


namespace core\forms\auth;

use Yii;
use core\entities\User\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\core\entities\User\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => 'Нет пользователя с этим адресом электронной почты.'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }
}
