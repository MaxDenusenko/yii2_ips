<?php


namespace core\forms\manage\Core;


use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\forms\CompositeForm;
use yii\helpers\ArrayHelper;

class TariffForm extends CompositeForm
{
    public $name;
    public $name_en;

    public $description;
    public $description_en;

    public $number;
    public $price;
    public $proxy_link;
    public $price_for_additional_ip;
    public $qty_proxy;
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
            [['number', 'name', 'price'], 'required'],
            [['number', 'price_for_additional_ip', 'category_id'], 'integer'],
            [['price'], 'double'],
            [['proxy_link', 'description', 'description_en', 'qty_proxy'], 'string'],
            [['name', 'name_en'], 'string', 'max' => 255],
//            [['name', 'number'], 'unique', 'targetClass' => Tariff::class, 'filter' => $this->_tariff ? ['<>', 'id', $this->_tariff->id] : null],
        ];
    }

    public static function categoryList()
    {
        return ArrayHelper::map(CategoryTariffs::find()->joinWith(['translation'])->orderBy('name')->asArray()->all(), 'id', 'translation.name');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '№',
            'name' => \Yii::t('frontend', 'Name'),
            'price' => \Yii::t('frontend', 'Price'),
            'status' => \Yii::t('frontend', 'Status'),
            'proxy_link' => \Yii::t('frontend', 'Link to proxy list'),
            'description' => \Yii::t('frontend', 'Description'),
            'price_for_additional_ip' => \Yii::t('frontend', 'Price for additional ip (% of face value)'),
            'qty_proxy' => \Yii::t('frontend', 'Number of proxies'),
            'category_id' => \Yii::t('frontend', 'Category'),
        ];
    }

    protected function internalForms(): array
    {
        return ['default', 'defaultTrial'];
    }
}
