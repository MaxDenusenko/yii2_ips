<?php
namespace core\forms\auth;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;
use core\entities\User\User;
use kartik\password\StrengthValidator;

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
    public $password_repeat;
    public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\core\entities\User\User', 'message' => \Yii::t('frontend', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email', 'full_name', 'telegram', 'gabber'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\core\entities\User\User', 'message' => \Yii::t('frontend', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 8],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],

            [['password'], StrengthValidator::className(), 'preset'=>'normal', 'userAttribute'=>'username'],

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
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'password_repeat' => 'Подтверждение пароля',
            'full_name' => 'ФИО',
            'telegram' => 'Telegram',
            'gabber' => 'Jabber',
        ];
    }
}
