<?php

namespace core\entities\Core;

use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "renewal_order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $product_user
 * @property string $product_hash
 * @property string $name
 * @property float $price
 * @property int $quantity
 * @property float $cost
 * @property string $currency
 *
 * @property Order $order
 * @property TariffAssignment $tariffAssignment
 * @property Tariff $tariff
 * @property User $productUser
 */
class RenewalOrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%renewal_order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_user', 'product_hash', 'name', 'price', 'cost', 'currency'], 'required'],
            [['order_id', 'product_id', 'product_user', 'quantity'], 'integer'],
            [['price', 'cost'], 'number'],
            [['product_hash', 'name', 'currency'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_hash'], 'exist', 'skipOnError' => true, 'targetClass' => TariffAssignment::className(), 'targetAttribute' => ['product_hash' => 'hash_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['product_user' => 'id']],
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
            'product_id' => \Yii::t('frontend', 'Tariff'),
            'product_user' => \Yii::t('frontend', 'User'),
            'product_hash' => \Yii::t('frontend', 'Bunch'),
            'name' => \Yii::t('frontend', 'Name'),
            'price' => \Yii::t('frontend', 'Price'),
            'quantity' => \Yii::t('frontend', 'Quantity'),
            'cost' => \Yii::t('frontend', 'Price'),
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
     * Gets query for [[ProductHash]].
     *
     * @return ActiveQuery
     */
    public function getTariffAssignment()
    {
        return $this->hasOne(TariffAssignment::className(), ['hash_id' => 'product_hash']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[ProductUser]].
     *
     * @return ActiveQuery
     */
    public function getProductUser()
    {
        return $this->hasOne(User::className(), ['id' => 'product_user']);
    }
}
