<?php

namespace core\forms\auth;

use core\entities\User\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['token', 'string', 'max' => 255],
        ];
    }
}
