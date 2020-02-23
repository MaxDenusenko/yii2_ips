<?php


namespace core\forms\manage\Core;


use core\entities\Core\PaymentMethod;
use core\forms\CompositeForm;
use yii\helpers\ArrayHelper;

/**
 * @property AdditionalIpOrderItemForm assignment
 */
class AdditionalIpOrderForm extends CompositeForm
{
    public $payment_method_id;
    public $comment;
    public $additional_ip;

    private $_order;

    public function __construct($config = [])
    {
        $this->assignment = new AdditionalIpOrderItemForm();

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
            [['additional_ip'], 'integer'],
            [['additional_ip'], 'required'],
            [['comment'], 'string', 'max' => 255],
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
            'additional_ip' => \Yii::t('frontend', 'Number of additional IP'),
        ];
    }

    protected function internalForms(): array
    {
        return ['assignment'];
    }
}
