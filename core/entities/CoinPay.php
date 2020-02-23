<?php

namespace core\entities;

use core\entities\Core\AdditionalOrderItem;
use core\entities\Core\Order;
use core\entities\Core\TariffAssignment;
use core\services\manage\Core\TariffAssignmentManageService;
use core\services\TransactionManager;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "coin_pay".
 *
 * @property int $id
 * @property int $order_id
 * @property string $charge_id
 * @property string $pay_link
 * @property string $identity
 * @property string $status
 *
 * @property Order $order
 *
 * @property TariffAssignment $tariff
 */
class CoinPay extends \yii\db\ActiveRecord
{
    private $oldRecord;
    public $transactionManager;

    const STATUS_NOT_PAID = 1;
    const STATUS_PAID = 2;

    const WEBHOOK_CREATED = 'charge:created';
    const WEBHOOK_CONFIRMED = 'charge:confirmed';
    const WEBHOOK_FAILED = 'charge:failed';
    const WEBHOOK_DELAYED = 'charge:delayed';
    const WEBHOOK_PENDING = 'charge:pending';
    const WEBHOOK_RESOLVED = 'charge:resolved';
    const PAI_RESOLVED = 'charge:no created';

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->transactionManager = new TransactionManager();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coin_pay}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'pay_link', 'identity'], 'required'],
            [['order_id'], 'integer'],
            [['pay_link', 'identity', 'status', 'charge_id'], 'string'],
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
            'order_id' => \Yii::t('frontend', 'Order'),
            'pay_link' => \Yii::t('frontend', 'Payment Link'),
            'identity' => \Yii::t('frontend', 'Identity'),
            'status' => \Yii::t('frontend', 'Status'),
            'charge_id' => \Yii::t('frontend', 'Payment'),
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

    public function afterFind()
    {
        $this->oldRecord = clone $this;
        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->status) {

            $order = $this->order;
            if (!$order || $order->isCanceled()) return parent::beforeSave($insert);

            $orderItem = false;

            switch ($order->type) {
                case Order::TYPE_TARIFF_PAI:
                    $orderItem = $order->orderItems;
                    break;

                case Order::TYPE_TARIFF_RENEWAL:
                    $orderItem = $order->renewalOrderItems;
                    break;

                case Order::TYPE_TARIFF_ADDITIONAL_IP:
                    $orderItem = $order->additionalOrderItems;
                    break;
            }

            if ($orderItem &&
                $this->oldRecord->status != $this->status) {

                $orderItem = array_shift($orderItem);
                $tariffAssignment = $orderItem->tariffAssignment;

                switch ($order->type) {
                    case Order::TYPE_TARIFF_PAI:
                    case Order::TYPE_TARIFF_RENEWAL:

                        switch ($this->status) {
                            case self::WEBHOOK_CONFIRMED:
                            case self::WEBHOOK_CREATED:

                                if ($tariffAssignment->status != TariffAssignment::STATUS_ACTIVE)
                                    $tariffAssignment->status = TariffAssignment::STATUS_DRAFT;

                                if (!$order->isPaid()) {

                                    $default = $tariffAssignment->tariff->default[0];
                                    $tariffAssignment->renewal($default->extend_minutes, $default->extend_hours, $default->extend_days, true);
                                    $tariffAssignment->addCouponUse($order->amount);
                                    $order->setPaid();
                                }

                                $additionalItems = AdditionalOrderItem::find()->where(['product_hash' => $tariffAssignment->hash_id])->joinWith(['order'])->all();

                                if (count($additionalItems)) {
                                    foreach ($additionalItems as $additionalItem) {
                                        if (!$additionalItem->order->isPaid()) {
                                            $additionalItem->order->canceled();
                                            $additionalItem->order->save();
                                        }
                                    }
                                }

                                $tariffAssignment->activatePause();
                                break;

                            case self::WEBHOOK_FAILED:
                                $order->canceled();
                                break;
                        }
                        $this->transactionManager->wrap(function () use($order, $tariffAssignment) {
                            $order->save();
                            $tariffAssignment->save();
                        });

                        break;

                    case Order::TYPE_TARIFF_ADDITIONAL_IP:

                        switch ($this->status) {
                            case self::WEBHOOK_CONFIRMED:
                            case self::WEBHOOK_CREATED:

                                if (!$order->isPaid()) {
                                    $tariffAssignment->addAdditionalIp($orderItem->additional_ip);
                                    $tariffAssignment->addCouponUse($order->amount);
                                    $order->setPaid();
                                }
                                break;

                            case self::WEBHOOK_FAILED:
                                $order->canceled();
                                break;
                        }
                        $this->transactionManager->wrap(function () use($order, $tariffAssignment) {
                            $order->save();
                            $tariffAssignment->save();
                        });

                        break;

                }
            }

        }

        return parent::beforeSave($insert);
    }
}
