<?php


namespace core\forms\manage\Core;


use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\forms\CompositeForm;
use yii\helpers\ArrayHelper;

class TariffForm extends CompositeForm
{
    public $name;
    public $number;
    public $price;
    public $proxy_link;
    public $description;
    public $price_for_additional_ip;
    public $qty_proxy;
    public $currency;
    public $category_id;

    private $_tariff;

    public function __construct(Tariff $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->name = $tariff->name;
            $this->number = $tariff->number;
            $this->price = $tariff->price;
            $this->qty_proxy = $tariff->qty_proxy;
            $this->price_for_additional_ip = $tariff->price_for_additional_ip;
            $this->description = $tariff->description;
            $this->proxy_link = $tariff->proxy_link;
            $this->currency = $tariff->currency;
            $this->category_id = $tariff->category_id;
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
            [['number', 'price_for_additional_ip', 'price', 'category_id'], 'integer'],
            [['proxy_link', 'description', 'qty_proxy', 'currency'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name', 'number'], 'unique', 'targetClass' => Tariff::class, 'filter' => $this->_tariff ? ['<>', 'id', $this->_tariff->id] : null],
        ];
    }

    public function categoryList()
    {
        return ArrayHelper::map(CategoryTariffs::find()->orderBy('name')->asArray()->all(), 'id', 'name');
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
            'price' => 'Цена',
            'status' => 'Статус',
            'proxy_link' => 'Ссылка на список прокси',
            'description' => 'Описание',
            'price_for_additional_ip' => 'Цена за доп ip (% от стоимости номинала)',
            'qty_proxy' => 'Количество прокси',
            'currency' => 'Валюта',
            'category_id' => 'Категория',
        ];
    }

    protected function internalForms(): array
    {
        return ['default', 'defaultTrial'];
    }
}
