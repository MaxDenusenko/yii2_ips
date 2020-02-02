<?php

namespace core\entities;

use core\entities\Core\Order;
use Yii;

/**
 * This is the model class for table "coin_pay".
 *
 * @property int $id
 * @property int $order_id
 * @property string $pay_link
 * @property string $identity
 * @property int $status
 *
 * @property Order $order
 */
class CoinPay extends \yii\db\ActiveRecord
{
    const STATUS_NOT_PAID = 1;
    const STATUS_PAID = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coin_pay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'pay_link', 'identity', 'status'], 'required'],
            [['order_id', 'status'], 'integer'],
            [['pay_link', 'identity'], 'string'],
            [['order_id'], 'exist', 'skipOnError' => false, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'pay_link' => 'Pay Link',
            'identity' => 'Identity',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
