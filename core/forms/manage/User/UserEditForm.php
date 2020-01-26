<?php


namespace core\forms\manage\User;


use core\entities\User\User;
use core\forms\CompositeForm;
use yii\base\Model;

class UserEditForm extends CompositeForm
{
    public $username;
    public $email;
    public $full_name;
    public $telegram;
    public $gabber;
    public $tariff_reminder;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->full_name = $user->full_name;
        $this->telegram = $user->telegram;
        $this->gabber = $user->gabber;
        $this->tariff_reminder = $user->tariff_reminder;
        $this->_user = $user;
        $this->tariffs = new TariffsFom($user);
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['tariff_reminder'] , 'integer'],
            [['username', 'email'] , 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email', 'full_name', 'telegram', 'gabber'] , 'string', 'max' => 255],
            [['username', 'email'] , 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
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
            'full_name' => 'ФИО',
            'telegram' => 'Telegram',
            'gabber' => 'Jabber',
            'tariff_reminder' => 'Напоминание о окончании тарифа (за n дней)',
        ];
    }

    protected function internalForms(): array
    {
        return  ['tariffs'];
    }
}
