<?php


namespace core\forms\manage\Core;


use core\entities\Core\Tariff;
use core\forms\CompositeForm;

class TariffForm extends CompositeForm
{
    public $name;
    public $number;
    public $quantity;
    public $price;
    public $proxy_link;
    public $description;
    public $price_for_additional_ip;
    public $qty_proxy;

    private $_tariff;

    public function __construct(Tariff $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->name = $tariff->name;
            $this->number = $tariff->number;
            $this->quantity = $tariff->quantity;
            $this->price = $tariff->price;
            $this->qty_proxy = $tariff->qty_proxy;
            $this->price_for_additional_ip = $tariff->price_for_additional_ip;
            $this->description = $tariff->description;
            $this->proxy_link = $tariff->proxy_link;
            $this->default = new TariffDefaultsForm($tariff->default[0]);
            $this->defaultTrial = new TariffDefaultsTrialForm($tariff->defaultTrial[0]);

            $this->_tariff = $tariff;
        } else {

            $this->default = new TariffDefaultsForm();
            $this->defaultTrial = new TariffDefaultsTrialForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['number', 'name'], 'required'],
            [['number', 'quantity', 'price', 'price_for_additional_ip', 'qty_proxy'], 'integer'],
            [['proxy_link', 'description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name', 'number'], 'unique', 'targetClass' => Tariff::class, 'filter' => $this->_tariff ? ['<>', 'id', $this->_tariff->id] : null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '№',
            'name' => 'Название',
            'quantity' => 'Колличество',
            'price' => 'Цена',
            'status' => 'Статус',
            'proxy_link' => 'Ссылка на список прокси',
            'description' => 'Описание',
            'price_for_additional_ip' => 'Цена за доп ip',
            'qty_proxy' => 'Количество прокси',
        ];
    }

    protected function internalForms(): array
    {
        return ['default', 'defaultTrial'];
    }
}
