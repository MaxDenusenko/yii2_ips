<?php

namespace core\forms;

use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
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
            'name' => 'Имя',
            'email' => 'Email',
            'subject' => 'Тема',
            'body' => 'Сообщение',
        ];
    }

}
