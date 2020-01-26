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
            $this->date_to = $tariff->date_to;
            $this->time_to = $tariff->time_to;
            $this->ip_quantity = $tariff->ip_quantity;
            $this->discount = $tariff->discount;
            $this->_tariff = $tariff;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['file_path', 'IPs', 'mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic'], 'required'],
            [['mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic', 'ip_quantity', 'discount'], 'integer'],
            [['file_path', 'IPs', 'date_to', 'time_to'], 'string'],
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
                $this->addError('IPs', "IP-адрес $IP указан верно.");
        }
    }

    public function ip_quantity_validator($attribute, $params)
    {
        $arr_ip = array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->IPs), array('')));

        if ($this->$attribute < count($arr_ip)) {
            $this->addError($attribute, 'Количество доступных IP меньше чем присвоенных сейчас.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tariff_id' => 'Тариф',
            'user_id' => 'Пользователь',
            'file_path' => 'Пути к конфиг-файлам',
            'status' => 'Статус',
            'IPs' => 'IPs',
            'mb_limit' => 'Ограничение по траффику',
            'quantity_incoming_traffic' => 'Количество потоков входящего трафика',
            'quantity_outgoing_traffic' => 'Количество потоков исходящего трафика',
            'date_to' => 'Дата(до)',
            'time_to' => 'Время(до)',
            'ip_quantity' => 'Количество доступных ip',
            'discount' => 'Скидка',
        ];
    }
}
