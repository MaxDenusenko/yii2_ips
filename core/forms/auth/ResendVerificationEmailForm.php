<?php


namespace core\forms\auth;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use core\entities\User\User;
use yii\base\Model;

class ResendVerificationEmailForm extends Model
{
    /**
     * @var string
     */
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
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => \Yii::t('frontend', 'There is no user with this email address.')
            ],
            [['reCaptcha'], ReCaptchaValidator2::className(),
                'uncheckedMessage' => \Yii::t('frontend', 'Please confirm that you are not a bot.')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('frontend', 'Email'),
            'reCaptcha' => false,
        ];
    }
}
