<?php


namespace core\forms\user;


use core\entities\User\User;
use yii\base\Model;

class ProfileEditForm extends Model
{
    public $username;
    public $email;
    public $full_name;
    public $telegram;
    public $gabber;
    public $_user;
    public $tariff_reminder;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->full_name = $user->full_name;
        $this->telegram = $user->telegram;
        $this->gabber = $user->gabber;
        $this->tariff_reminder = $user->tariff_reminder;
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('frontend', 'Login'),
            'email' => \Yii::t('frontend', 'Email'),
            'full_name' => \Yii::t('frontend', 'Full name'),
            'telegram' => 'Telegram',
            'gabber' => 'Jabber',
            'tariff_reminder' => \Yii::t('frontend', 'Reminder of the end of the tariff (for n days)'),
        ];
    }

    public function rules()
    {
        return [
            [['tariff_reminder'] , 'integer', 'min' => 1],
            [['username', 'email'] , 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email', 'full_name', 'telegram', 'gabber'] , 'string', 'max' => 255],
            [['username', 'email'] , 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }
}
