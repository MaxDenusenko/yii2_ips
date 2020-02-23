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
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message' => \Yii::t('frontend', 'Passwords do not match') ],

            [['password'], StrengthValidator::className(), 'preset'=>'normal', 'userAttribute'=>'username'],

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
            'username' => \Yii::t('frontend', 'Login'),
            'email' => \Yii::t('frontend', 'Email'),
            'password' => \Yii::t('frontend', 'Password'),
            'password_repeat' => \Yii::t('frontend', 'Password confirmation'),
            'full_name' => \Yii::t('frontend', 'Full name'),
            'telegram' => 'Telegram',
            'gabber' => 'Jabber',
            'reCaptcha' => false,
        ];
    }
}
