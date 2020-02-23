<?php


namespace core\forms\manage\Core;


use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use yii\base\Model;
use core\helpers\CurrencyHelper;

class AdditionalIpOrderItemForm extends Model
{
    public $name;
    public $price;
    public $cost;
    public $product_id;
    public $product_hash;
    public $product_user;
    public $currency;
    public $quantity;

    private $_tariff;

    public function __construct(TariffAssignment $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->name = $tariff->tariff->name;
            $this->price = $tariff->getPrice();
            $this->cost = $this->price;
            $this->product_id = $tariff->tariff_id;
            $this->product_hash = $tariff->hash_id;
            $this->product_user = $tariff->user_id;
            $this->_tariff = $tariff;
            $this->quantity = 1;
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_hash', 'product_user'], 'required'],
            [['product_id', 'product_user'], 'integer'],
            [['price', 'cost'], 'double'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_id', 'product_hash', 'product_user'],
                'exist', 'skipOnError' => true, 'targetClass' => TariffAssignment::className(),
                'targetAttribute' => ['product_id' => 'tariff_id', 'product_hash' => 'hash_id', 'product_user' => 'user_id']],
        ];
    }

    public function beforeValidate()
    {
        if (!$this->price && $product = TariffAssignment::find()
                ->where(['hash_id' => $this->product_hash])
                ->notCancel()->one()) {

            /** @var TariffAssignment $product */
            if ($product->isPaid()) {
                $this->name = $product->tariff->name;
                $this->price = $product->getPrice();
                $this->cost = $this->price;
                $this->product_id = $product->tariff_id;
                $this->product_hash = $product->hash_id;
                $this->product_user = $product->user_id;
                $this->quantity = 1;
                $this->currency = CurrencyHelper::getActiveCode();;
            }
        }

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => false,
            'product_hash' => false,
        ];
    }
}
