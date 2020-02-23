<?php


namespace core\forms\manage\Core;


use core\entities\Core\TariffDefaults;
use yii\base\Model;

class TariffDefaultsTrialForm extends Model
{
    public $mb_limit;
    public $quantity_incoming_traffic;
    public $quantity_outgoing_traffic;
    public $file_path;
    public $ip_quantity;
    public $type;
    public $extend_days;
    public $extend_hours;
    public $extend_minutes;

    private $_tariff;

    public function __construct(TariffDefaults $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->mb_limit = $tariff->mb_limit;
            $this->quantity_incoming_traffic = $tariff->quantity_incoming_traffic;
            $this->quantity_outgoing_traffic = $tariff->quantity_outgoing_traffic;
            $this->file_path = $tariff->file_path;
            $this->ip_quantity = $tariff->ip_quantity;
            $this->type = $tariff->type;
            $this->extend_days = $tariff->extend_days;
            $this->extend_hours = $tariff->extend_hours;
            $this->extend_minutes = $tariff->extend_minutes;
            $this->_tariff = $tariff;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['mb_limit', 'ip_quantity', 'type', 'extend_days', 'extend_hours', 'extend_minutes'], 'integer'],
            [['file_path', 'quantity_incoming_traffic', 'quantity_outgoing_traffic'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mb_limit' => \Yii::t('frontend', 'Traffic limit'),
            'quantity_incoming_traffic' => \Yii::t('frontend', 'Number of incoming traffic streams'),
            'quantity_outgoing_traffic' => \Yii::t('frontend', 'Number of outgoing traffic flows'),
            'ip_quantity' => \Yii::t('frontend', 'Number of ip available'),
            'file_path' => \Yii::t('frontend', 'Paths to config files'),
            'type' => \Yii::t('frontend', 'Type'),
            'extend_days' => \Yii::t('frontend', 'Renew for (days)'),
            'extend_hours' => \Yii::t('frontend', 'Renew by (hours)'),
            'extend_minutes' => \Yii::t('frontend', 'Renew by (minutes)'),
        ];
    }
}