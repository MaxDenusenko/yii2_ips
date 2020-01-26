<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffQuery;
use core\entities\User\User;
use core\forms\manage\Core\TariffDefaultsForm;
use core\services\manage\Core\TariffDefaultsManageService;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariffs".
 *
 * @property int $id
 * @property int $qty_proxy
 * @property int $number
 * @property string $name
 * @property string $description
 * @property string $proxy_link
 * @property int|null $quantity
 * @property int|null $price
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
     * @param $quantity
     * @param $price
     * @param $status
     * @param $proxy_link
     * @param $description
     * @param $price_for_additional_ip
     * @param $qty_proxy
     * @return static
     */
    public static function create($name, $number, $quantity, $price, $status, $proxy_link, $description, $price_for_additional_ip, $qty_proxy)
    {
        $tariff = new static();
        $tariff->name = $name;
        $tariff->number = $number;
        $tariff->quantity = $quantity;
        $tariff->price = $price;
        $tariff->status = $status;
        $tariff->proxy_link = $proxy_link;
        $tariff->description = $description;
        $tariff->price_for_additional_ip = $price_for_additional_ip;
        $tariff->qty_proxy = $qty_proxy;
        return $tariff;
    }

    /**
     * @param $name
     * @param $number
     * @param $quantity
     * @param $price
     * @param $proxy_link
     * @param $description
     * @param $price_for_additional_ip
     * @param $qty_proxy
     */
    public function edit($name, $number, $quantity, $price, $proxy_link, $description, $price_for_additional_ip, $qty_proxy)
    {
        $this->name = $name;
        $this->number = $number;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->proxy_link = $proxy_link;
        $this->description = $description;
        $this->price_for_additional_ip = $price_for_additional_ip;
        $this->qty_proxy = $qty_proxy;
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
            [['number', 'quantity', 'price', 'status', 'price_for_additional_ip', 'qty_proxy'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['proxy_link', 'description'], 'string'],
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
            'quantity' => 'Колличество',
            'price' => 'Цена',
            'status' => 'Статус',
            'proxy_link' => 'Ссылка на список прокси',
            'description' => 'Описание',
            'price_for_additional_ip' => 'Цена за доп ip',
            'qty_proxy' => 'Количество прокси',
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
}
