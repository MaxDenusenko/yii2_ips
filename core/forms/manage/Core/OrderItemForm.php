<?php


namespace core\forms\manage\Core;


use core\entities\Core\Tariff;
use yii\base\Model;
use core\helpers\CurrencyHelper;

class OrderItemForm extends Model
{
    public $name;
    public $price;
    public $quantity;
    public $cost;
    public $product_id;
    public $currency;

    private $_tariff;

    public function __construct(Tariff $tariff = null, $config = [])
    {
        if ($tariff) {
            $this->name = $tariff->name;
            $this->price = $tariff->getPrice();
            $this->quantity = 1;
            $this->cost = $this->price;
            $this->product_id = $tariff->id;
            $this->currency = CurrencyHelper::getActiveCode();
            $this->_tariff = $tariff;
        }

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['price', 'cost'], 'double'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    public function beforeValidate()
    {
        if (!$this->price && $product = Tariff::find()->where(['id' => $this->product_id])->active()->one()) {

            $this->name = $product->name;
            $this->price = $product->getPrice();
            $this->quantity = 1;
            $this->cost = $product->price;
            $this->currency = CurrencyHelper::getActiveCode();
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
        ];
    }
}
