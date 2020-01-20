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
            'rememberMe' => 'Запомнить меня',
            'password' => 'Пароль',
        ];
    }
}
