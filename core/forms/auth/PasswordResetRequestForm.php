<?php
namespace core\forms\auth;

use Yii;
use yii\base\Model;
use core\entities\User\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
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
                'filter' => ['status' => User::STATUS_ACTIVE],
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
