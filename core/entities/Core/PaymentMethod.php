<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "payment_methods".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 *
 * @property Order[] $orders
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_methods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label'], 'required'],
            [['name', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => \Yii::t('frontend', 'Code'),
            'label' => \Yii::t('frontend', 'Name')
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['payment_method_id' => 'id']);
    }
}
