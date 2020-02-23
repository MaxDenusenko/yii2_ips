<?php


namespace core\forms\manage\Core;


use core\entities\Core\Coupons;
use core\entities\Core\Order;
use core\entities\Core\PaymentMethod;
use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\forms\CompositeForm;
use yii\helpers\ArrayHelper;

class RenewalForm extends CompositeForm
{
    public $payment_method_id;
    public $comment;
    public $additional_id;
    public $renew_with_additional_ip;

    private $_order;

    public function __construct($config = [])
    {
        $this->assignment = new RenewalItemForm();

        parent::__construct($config);
    }

    public function getPaymentList()
    {
        return ArrayHelper::map(PaymentMethod::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string', 'max' => 255],
            [['additional_id', 'renew_with_additional_ip'], 'integer'],
            [['payment_method_id'], 'exist', 'skipOnError' => false, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment' => false,
            'payment_method_id' => \Yii::t('frontend', 'Payment method'),
            'additional_id' => \Yii::t('frontend', 'Number of additional IP'),
            'renew_with_additional_ip' => \Yii::t('frontend', 'Renew with additional IP?'),
        ];
    }

    protected function internalForms(): array
    {
        return ['assignment'];
    }
}
