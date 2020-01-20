<?php
namespace core\forms\auth;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use core\entities\User\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
        ];
    }
}
