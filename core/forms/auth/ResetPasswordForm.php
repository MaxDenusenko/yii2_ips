<?php
namespace core\forms\auth;

use kartik\password\StrengthValidator;
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
            ['password', 'string', 'min' => 8],

            [['password'], StrengthValidator::className(), 'preset'=>'normal', 'usernameValue' => 'admin']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('frontend', 'New password'),
        ];
    }
}
