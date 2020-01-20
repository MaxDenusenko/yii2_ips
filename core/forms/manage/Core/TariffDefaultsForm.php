<?php


namespace core\forms\manage\Core;


use core\entities\Core\TariffDefaults;
use yii\base\Model;

class TariffDefaultsForm extends Model
{
    public $mb_limit;
    public $quantity_incoming_traffic;
    public $quantity_outgoing_traffic;
    public $name;

    private $_tariff;

    public function __construct(TariffDefaults $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->mb_limit = $tariff->mb_limit;
            $this->quantity_incoming_traffic = $tariff->quantity_incoming_traffic;
            $this->quantity_outgoing_traffic = $tariff->quantity_outgoing_traffic;
            $this->name = $tariff->name;
            $this->_tariff = $tariff;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
        ];
    }
}
