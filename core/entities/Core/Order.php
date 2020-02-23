<?php

namespace core\entities\Core;

use core\entities\CoinPay;
use core\entities\Core\queries\OrderQuery;
use core\entities\User\User;
use core\forms\manage\Core\OrderItemForm;
use core\forms\manage\Core\RenewalItemForm;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property string|null $comment
 * @property float $amount
 * @property int $status
 * @property int $created
 * @property int $updated
 * @property int $type
 * @property int $time_left
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 * @property RenewalOrderItem[] $renewalOrderItems
 * @property AdditionalOrderItem[] $additionalOrderItems
 * @property PaymentMethod $paymentMethod
 */
class Order extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELED = 3;

    const TYPE_TARIFF_PAI = 1;
    const TYPE_TARIFF_RENEWAL = 2;
    const TYPE_TARIFF_ADDITIONAL_IP = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    public static function create($payment_method_id, $comment, $amount, $user_id = null)
    {
        $order = new static();
        $order->payment_method_id = $payment_method_id;
        $order->comment = $comment;
        $order->user_id = $user_id ? $user_id : Yii::$app->user->id;
        $order->amount = $amount;
        return $order;
    }

    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'amount'], 'required'],
            [['user_id', 'status', 'created', 'updated', 'type', 'time_left'], 'integer'],
            [['amount'], 'number'],
            [['comment'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => false, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('frontend', 'User'),
            'comment' => Yii::t('frontend', 'A comment'),
            'amount' => Yii::t('frontend', 'Order price'),
            'status' => Yii::t('frontend', 'Status'),
            'created' => Yii::t('frontend', 'Date of creation'),
            'updated' => Yii::t('frontend', 'Update date'),
            'payment_method_id' => Yii::t('frontend', 'Payment method'),
            'type' => Yii::t('frontend', 'Type'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[RenewalOrderItem]].
     *
     * @return ActiveQuery
     */
    public function getRenewalOrderItems()
    {
        return $this->hasMany(RenewalOrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[RenewalOrderItem]].
     *
     * @return ActiveQuery
     */
    public function getAdditionalOrderItems()
    {
        return $this->hasMany(AdditionalOrderItem::className(), ['order_id' => 'id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    // при вставке новой записи присвоить атрибутам created
                    // и updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'updated'],
                    // при обновлении существующей записи  присвоить атрибуту
                    // updated значение метки времени UNIX
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                // если вместо метки времени UNIX используется DATETIME
//                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['user', 'orderItems', 'renewalOrderItems', 'additionalOrderItems'],
            ]
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param $orderItemForm OrderItemForm
     */
    public function addItem($orderItemForm)
    {
        $orderItem = new OrderItem($orderItemForm);
        $this->orderItems = $orderItem;
    }

    public function canceled()
    {
        $this->status = self::STATUS_CANCELED;
    }

    public function isCanceled(): bool
    {
        return $this->status == self::STATUS_CANCELED;
    }

    /**
     * @param $orderRenewalItemForm RenewalItemForm
     */
    public function addRenewalItem($orderRenewalItemForm)
    {
        $renewalOrderItem = new RenewalOrderItem($orderRenewalItemForm);
        $this->renewalOrderItems = $renewalOrderItem;
    }

    public function addAdditionalIdItem($additionalIpOrderItemForm, int $additional_ip, float $price)
    {
        $additionalIpOrderItem = new AdditionalOrderItem($additionalIpOrderItemForm);
        $additionalIpOrderItem->additional_ip = $additional_ip;
        $additionalIpOrderItem->price = $price;
        $additionalIpOrderItem->cost = $price;
        $this->additionalOrderItems = $additionalIpOrderItem;
    }

    public function setPaid()
    {
        $this->status = Order::STATUS_PAID;
    }

    public static function find(): OrderQuery
    {
        return new OrderQuery(static::class);
    }

    public function getFrontTimeLeft()
    {
        return $this->downCounter($this->getTimeLeftFormatDateTime());
    }

    public function getTimeLeftFormatDateTime()
    {
        $timestamp = strtotime("+{$this->getTimeLeft()} minutes",time());
        return date('Y-m-d H:i:s', $timestamp);
    }

    public function getTimeLeft()
    {
        return $this->time_left;
    }

    public function downCounter($date){
        $check_time = strtotime($date) - time();
        if($check_time <= 0){
            return false;
        }

        $days = floor($check_time/86400);
        $hours = floor(($check_time%86400)/3600);
        $minutes = floor(($check_time%3600)/60);
        $seconds = $check_time%60;

        $str = '';
        if($days > 0) $str .= $this->declension($days,array('день','дня','дней')).' ';
        if($hours > 0) $str .= $this->declension($hours,array('час','часа','часов')).' ';
        if($minutes > 0) $str .= $this->declension($minutes,array('минута','минуты','минут')).' ';
        if($seconds > 0) $str .= $this->declension($seconds,array('секунда','секунды','секунд'));

        return $str;
    }

    public static function declension($digit ,$expr, $onlyWord = false){

        if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i=preg_replace('/[^0-9]+/s','',$digit)%100;
        if($onlyWord) $digit='';

        if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
        else
        {
            $i%=10;
            if($i==1) $res=$digit.' '.$expr[0];
            elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
            else $res=$digit.' '.$expr[2];
        }

        return trim($res);
    }
}
