<?php


namespace core\forms\manage\Core;


use core\entities\Core\TariffAssignment;
use himiklab\yii2\recaptcha\ReCaptchaValidator2;
use yii\base\Model;

class TariffAssignmentEditIpsForm extends Model
{
    public $IPs;
    public $IPsArr;
    public $reCaptcha;

    private $_tariff;

    public function __construct(TariffAssignment $tariff, $config = [])
    {
        $this->IPs = $tariff->IPs;
        $this->_tariff = $tariff;
        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [
            'IPs' => 'IPs',
        ];
    }

    public function beforeValidate()
    {
        $this->IPsArr = array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->IPs), array('')));

        $this->IPsArr = array_unique($this->IPsArr);

        return parent::beforeValidate();
    }

    public function ip_arr_validator($attribute, $params) {

        if (count($this->$attribute) > $this->_tariff->ip_quantity)
            $this->addError('IPs', "Можно добавить только {$this->_tariff->ip_quantity} IP.");

        foreach ($this->$attribute as $ip) {

            if (!filter_var($ip, FILTER_VALIDATE_IP))
                $this->addError('IPs', "IP-адрес $ip указан верно.");
        }
    }

    public function rules(): array
    {
        return [
            [['IPs'] , 'string'],
            [['IPsArr'] , 'ip_arr_validator'],
            [['reCaptcha'], ReCaptchaValidator2::className(),
                'uncheckedMessage' => 'Пожалуйста, подтвердите, что вы не бот.'],
        ];
    }
}
