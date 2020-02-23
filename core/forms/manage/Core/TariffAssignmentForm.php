<?php


namespace core\forms\manage\Core;


use core\entities\Core\TariffAssignment;
use yii\base\Model;

class TariffAssignmentForm extends Model
{
    public $file_path;
    public $IPs;
    public $mb_limit;
    public $quantity_incoming_traffic;
    public $quantity_outgoing_traffic;
    public $date_to;
    public $time_to;
    public $ip_quantity;
    public $discount;

    private $_tariff;

    public function __construct(TariffAssignment $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->file_path = $tariff->file_path;
            $this->IPs = $tariff->IPs;
            $this->mb_limit = $tariff->mb_limit;
            $this->quantity_incoming_traffic = $tariff->quantity_incoming_traffic;
            $this->quantity_outgoing_traffic = $tariff->quantity_outgoing_traffic;
//            $this->date_to = $tariff->date_to;
//            $this->time_to = $tariff->time_to;
            $this->ip_quantity = $tariff->ip_quantity;
            $this->discount = $tariff->discount;
            $this->_tariff = $tariff;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['mb_limit', 'ip_quantity', 'discount'], 'integer'],
            [['file_path', 'IPs', 'date_to', 'time_to', 'quantity_incoming_traffic', 'quantity_outgoing_traffic',], 'string'],
            [['ip_quantity'], 'ip_quantity_validator'],
            [['IPs'], 'ip_validator'],
        ];
    }

    public function ip_validator($attribute, $params) {

        $arr_ip = array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->$attribute), array('')));

        $this->$attribute = implode("\n", $arr_ip);

        foreach ($arr_ip as $IP) {
            if (!filter_var($IP, FILTER_VALIDATE_IP))
                $this->addError('IPs', \Yii::t('frontend', 'IP address')." $IP ".\Yii::t('frontend', 'not specified correctly.'));
        }
    }

    public function ip_quantity_validator($attribute, $params)
    {
        $arr_ip = array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->IPs), array('')));

        if ($this->$attribute < count($arr_ip)) {
            $this->addError($attribute, \Yii::t('frontend', 'The number of available IPs is less than assigned now.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tariff_id' => \Yii::t('frontend', 'Tariff'),
            'user_id' => \Yii::t('frontend', 'User'),
            'file_path' => \Yii::t('frontend', 'Paths to config files'),
            'status' => \Yii::t('frontend', 'Status'),
            'IPs' => \Yii::t('frontend', 'IPs'),
            'mb_limit' => \Yii::t('frontend', 'Traffic limit'),
            'quantity_incoming_traffic' => \Yii::t('frontend', 'Number of incoming traffic streams'),
            'quantity_outgoing_traffic' => \Yii::t('frontend', 'Number of outgoing traffic flows'),
            'date_to' => \Yii::t('frontend', 'Date (before)'),
            'time_to' => \Yii::t('frontend', 'Time (before)'),
            'ip_quantity' => \Yii::t('frontend', 'Number of ip available'),
            'discount' => \Yii::t('frontend', 'A discount %'),
        ];
    }
}
