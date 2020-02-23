<?php


namespace core\forms\manage\Core;


use core\entities\Core\Coupons;
use core\entities\Core\Order;
use core\entities\Core\PaymentMethod;
use core\entities\Core\Tariff;
use core\forms\CompositeForm;
use yii\helpers\ArrayHelper;

class OrderForm extends CompositeForm
{
    public $payment_method_id;
    public $comment;
    public $trial = false;
    public $additional_id;
    public $coupon_code;

    private $_order;

    public function __construct(Order $order = null, $config = [])
    {
        if ($order) {
            $this->payment_method_id = $order->payment_method_id;
            $this->comment = $order->comment;
            $this->product = new OrderItemForm(Tariff::findOne($order->orderItems[0]));
            $this->_order = $order;
        } else {
            $this->product = new OrderItemForm();
        }

        parent::__construct($config);
    }

    public function getPaymentList()
    {
        return ArrayHelper::map(PaymentMethod::find()->orderBy('name')->asArray()->all(), 'id', 'label');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trial'], 'safe'],
            [['additional_id'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['payment_method_id'], 'exist', 'skipOnError' => false, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
            [['coupon_code'], 'exist', 'skipOnError' => false, 'targetClass' => Coupons::className(), 'targetAttribute' => ['coupon_code' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => false,
            'trial' => false,
            'payment_method_id' => \Yii::t('frontend', 'Payment method'),
            'additional_id' => \Yii::t('frontend', 'Number of additional IP'),
            'coupon_code' => \Yii::t('frontend', 'Coupon'),
        ];
    }

    protected function internalForms(): array
    {
        return ['product'];
    }
}
