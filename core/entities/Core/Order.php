<?php

namespace core\entities\Core;

use core\entities\User\User;
use core\forms\manage\Core\OrderItemForm;
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
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 * @property PaymentMethod $paymentMethod
 */
class Order extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_PAID = 2;

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
            [['user_id', 'status', 'created', 'updated'], 'integer'],
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
            'user_id' => 'Пользователь',
            'comment' => 'Комментарий',
            'amount' => 'Сумма заказа',
            'status' => 'Статус',
            'created' => 'Дата создания',
            'updated' => 'Дата обновления',
            'payment_method_id' => 'Способ оплаты',
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
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['user', 'orderItems'],
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
}
