<?php


namespace core\forms\manage\Core;


use core\entities\Core\Tariff;
use yii\base\Model;

class TariffForm extends Model
{
    public $name;
    public $number;
    public $quantity;
    public $price;

    private $_tariff;

    public function __construct(Tariff $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->name = $tariff->name;
            $this->number = $tariff->number;
            $this->quantity = $tariff->quantity;
            $this->price = $tariff->price;
            $this->_tariff = $tariff;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['number', 'name'], 'required'],
            [['number', 'quantity', 'price'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'number'], 'unique', 'targetClass' => Tariff::class, 'filter' => $this->_tariff ? ['<>', 'id', $this->_tariff->id] : null],
        ];
    }
}
