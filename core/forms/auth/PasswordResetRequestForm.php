<?php
namespace core\forms\auth;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;
use core\entities\User\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $reCaptcha;

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
            [['reCaptcha'], ReCaptchaValidator2::className(),
                'uncheckedMessage' => 'Пожалуйста, подтвердите, что вы не бот.'],
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
