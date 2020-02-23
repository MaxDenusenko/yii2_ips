<?php
namespace core\forms\auth;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
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
            'rememberMe' => \Yii::t('frontend', 'Remember me'),
            'password' => \Yii::t('frontend', 'Password'),
            'reCaptcha' => false,
        ];
    }
}
