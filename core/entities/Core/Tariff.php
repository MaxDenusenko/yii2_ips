<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffQuery;
use core\forms\manage\Core\TariffDefaultsForm;
use core\helpers\converter\CurrencyConverter;
use core\helpers\CurrencyHelper;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "tariffs".
 *
 * @property int $id
 * @property string $qty_proxy
 * @property int $number
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property string $proxy_link
 * @property float $price
 * @property int $status
 * @property int $price_for_additional_ip
 * @property TariffDefaults [] default
 * @property TariffDefaults [] defaultTrial
 */
class Tariff extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;

    /**
     * @param $name
     * @param $number
     * @param $price
     * @param $status
     * @param $proxy_link
     * @param $description
     * @param $price_for_additional_ip
     * @param $qty_proxy
     * @param $category_id
     * @return static
     */
    public static function create($name, $number, $price, $status, $proxy_link, $description, $price_for_additional_ip, $qty_proxy, $category_id)
    {
        $tariff = new static();
        $tariff->name = $name;
        $tariff->number = $number;
        $tariff->price = $price;
        $tariff->status = $status;
        $tariff->proxy_link = $proxy_link;
        $tariff->description = $description;
        $tariff->price_for_additional_ip = $price_for_additional_ip;
        $tariff->qty_proxy = $qty_proxy;
        $tariff->category_id = $category_id;
        return $tariff;
    }

    /**
     * @param $name
     * @param $number
     * @param $price
     * @param $proxy_link
     * @param $description
     * @param $price_for_additional_ip
     * @param $qty_proxy
     * @param $currency
     */
    public function edit($name, $number, $price, $proxy_link, $description, $price_for_additional_ip, $qty_proxy, $category_id)
    {
        $this->name = $name;
        $this->number = $number;
        $this->price = $price;
        $this->proxy_link = $proxy_link;
        $this->description = $description;
        $this->price_for_additional_ip = $price_for_additional_ip;
        $this->qty_proxy = $qty_proxy;
        $this->category_id = $category_id;
    }

    public function getPrice($active_curr_code = null)
    {
        $active_curr = $active_curr_code ? $active_curr_code : CurrencyHelper::getActiveCode();
        $base_curr = CurrencyHelper::getBaseCode();

        if (!$active_curr || !$base_curr) {
            throw new Exception('не задана базовая и активная валюта');
        }

        $price = $this->price;
        if ($active_curr !== $base_curr) {

            $converter = new CurrencyConverter();
            $rate =  $converter->convert($base_curr, $active_curr);
            $price = $price*$rate;
        }

        return round($price, 2);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tariffs}}';
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Tariff is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Tariff is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'name'], 'required'],
            [['number', 'status', 'price_for_additional_ip', 'category_id'], 'integer'],
            [['price'], 'double'],
            [['name'], 'string', 'max' => 255],
            [['proxy_link', 'description', 'qty_proxy'], 'string'],
            [['name'], 'unique'],
            [['number'], 'unique'],
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
            'price' => 'Цена',
            'status' => 'Статус',
            'proxy_link' => 'Ссылка на список прокси',
            'description' => 'Описание',
            'price_for_additional_ip' => 'Цена за доп ip (% от стоимости номинала)',
            'qty_proxy' => 'Количество прокси',
            'category_id' => 'Категория',
        ];
    }

    public static function find(): TariffQuery
    {
        return new TariffQuery(static::class);
    }

    public function getDefault(): ActiveQuery
    {
        return $this->hasMany(TariffDefaults::class, ['tariff_id' => 'id'])->where(['type' => TariffDefaults::TYPE_SIMPLE]);
    }

    public function getDefaultTrial(): ActiveQuery
    {
        return $this->hasMany(TariffDefaults::class, ['tariff_id' => 'id'])->where(['type' => TariffDefaults::TYPE_TRIAL]);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CategoryTariffs::class, ['id' => 'category_id']);
    }

    public function getTariffAssignment(): ActiveQuery
    {
        return $this->hasMany(TariffAssignment::class, ['tariff_id' => 'id']);
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['default', 'defaultTrial'],
            ]
        ];
    }

    public function beforeDelete()
    {
        $tariffs = $this->tariffAssignment;

        if(count($tariffs)) {
            Yii::$app->session->setFlash(
                'warning',
                'Нельзя удалить тариф, который имеет связи'
            );
            return false;
        }

        return parent::beforeDelete();
    }

    /**
     * @param $default TariffDefaultsForm
     */
    public function addDefault($default)
    {
        $default = new TariffDefaults($default);
        $default->type = TariffDefaults::TYPE_SIMPLE;
        $this->default = $default;
    }

    public function addDefaultTrial($defaultTrial)
    {
        $default = new TariffDefaults($defaultTrial);
        $default->type = TariffDefaults::TYPE_TRIAL;
        $this->defaultTrial = $default;
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->name;
    }
}
