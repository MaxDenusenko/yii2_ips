<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffQuery;
use core\forms\ActiveRecordCompositeForm;
use core\forms\manage\Core\TariffDefaultsForm;
use core\forms\manage\Core\TariffDefaultsTrialForm;
use core\helpers\converter\CurrencyConverter;
use core\helpers\CurrencyHelper;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
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
 *
 * @property TariffDefaultsForm [] defaultComposite
 * @property TariffDefaultsTrialForm [] defaultTrialComposite
 */
class Tariff extends ActiveRecordCompositeForm
{
    private $oldRecord;

    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->setComposite();
    }

    public function setComposite()
    {
        $this->defaultComposite = new TariffDefaultsForm(isset($this->default[0]) ? $this->default[0] : null);
        $this->defaultTrialComposite = new TariffDefaultsTrialForm(isset($this->defaultTrial[0]) ? $this->defaultTrial[0] : null);
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
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['proxy_link', 'qty_proxy'], 'string'],
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
            'name' => Yii::t('frontend', 'Name'),
            'price' => Yii::t('frontend', 'Price'),
            'status' => Yii::t('frontend', 'Status'),
            'proxy_link' => Yii::t('frontend', 'Link to proxy list'),
            'description' => Yii::t('frontend', 'Description'),
            'price_for_additional_ip' => Yii::t('frontend', 'Price for additional ip (% of face value)'),
            'qty_proxy' => Yii::t('frontend', 'Number of proxies'),
            'category_id' => Yii::t('frontend', 'Category'),
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

    public function getTariffsLang(): ActiveQuery
    {
        return $this->hasMany(TariffsLang::class, ['tariff_id' => 'id']);
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
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => Yii::$app->params['languages'],
                'languageField' => 'language',
                //'localizedPrefix' => '',
                //'requireTranslations' => false,
                'dynamicLangClass' => true,
                'langClassName' => TariffsLang::className(),
                'defaultLanguage' => Yii::$app->sourceLanguage,
                'langForeignKey' => 'tariffs_id',
                'tableName' => "{{%tariffs_lang}}",
                'attributes' => [
                    'name', 'description'
                ]
            ],
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['default', 'defaultTrial'],
            ],
        ];
    }

    public function beforeDelete()
    {
        $tariffs = $this->tariffAssignment;

        if(count($tariffs)) {
            Yii::$app->session->setFlash(
                'warning',
                Yii::t('frontend', 'You cannot delete a tariff that has links')
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

    public function afterFind()
    {
        $this->oldRecord = clone $this;
        $this->setComposite();

        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->oldRecord) {

            if (
                ($this->oldRecord->price != $this->price)
                ||
                ($this->oldRecord->price_for_additional_ip != $this->price_for_additional_ip)
            ) {

                $renewalItems = RenewalOrderItem::find()->where(['product_id' => $this->id])->joinWith(['order'])->all();

                if (count($renewalItems)) {
                    foreach ($renewalItems as $renewalItem) {
                        if (!$renewalItem->order->isPaid()) {
                            $renewalItem->order->canceled();
                            $renewalItem->order->save();
                        }
                    }
                }

                $orderItems = OrderItem::find()->where(['product_id' => $this->id])->joinWith(['order'])->all();

                if (count($orderItems)) {
                    foreach ($orderItems as $orderItem) {
                        if (!$orderItem->order->isPaid()) {
                            $orderItem->order->delete();
                        }
                    }
                }

                $additionalItems = AdditionalOrderItem::find()->where(['product_id' => $this->id])->joinWith(['order'])->all();

                if (count($additionalItems)) {
                    foreach ($additionalItems as $additionalItem) {
                        if (!$additionalItem->order->isPaid()) {
                            $additionalItem->order->delete();
                        }
                    }
                }

            }
        }
        return parent::beforeSave($insert);
    }

    protected function internalForms(): array
    {
        return ['defaultComposite', 'defaultTrialComposite'];
    }
}
