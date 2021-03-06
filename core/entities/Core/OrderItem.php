<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $name
 * @property string $currency
 * @property float $price
 * @property int $quantity
 * @property float $cost
 *
 * @property Order $order
 * @property Tariff $product
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'name', 'price', 'quantity', 'cost'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price', 'cost'], 'number'],
            [['name', 'currency'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => \Yii::t('frontend', 'Order'),
            'product_id' => \Yii::t('frontend', 'Product'),
            'name' => \Yii::t('frontend', 'Product Name'),
            'price' => \Yii::t('frontend', 'The price of the product'),
            'quantity' => \Yii::t('frontend', 'Quantity'),
            'cost' => \Yii::t('frontend', 'Cost = Price * Qty'),
            'currency' => \Yii::t('frontend', 'Currency'),
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getTariffAssignment()
    {
        return $this->hasOne(TariffAssignment::className(), ['order_item_id' => 'id']);
    }
}
