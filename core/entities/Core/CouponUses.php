<?php

namespace core\entities\Core;

use core\entities\User\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "coupon_uses".
 *
 * @property int $id
 * @property string $date_use
 * @property int $coupon_id
 * @property int $user_id
 * @property string $tariff_assignment_hash_id
 * @property float $sum
 *
 * @property TariffAssignment $tariffAssignment
 * @property Coupons $coupon
 * @property User $user
 */

class CouponUses extends ActiveRecord
{
    /**
     * @param int $coupon_id
     * @param int $user_id
     * @param string $tariffAssignment_hash_id
     * @param float $sum
     * @return static
     */
    public static function create(int $coupon_id, int $user_id, string $tariffAssignment_hash_id, float $sum)
    {
        $coupon_use = new static();
        $coupon_use->coupon_id = $coupon_id;
        $coupon_use->user_id = $user_id;
        $coupon_use->tariff_assignment_hash_id = $tariffAssignment_hash_id;
        $coupon_use->sum = $sum;
        return $coupon_use;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_use',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupon_uses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coupon_id', 'user_id'], 'required'],
            [['date_use'], 'safe'],
            [['sum'], 'double'],
            [['coupon_id', 'user_id'], 'integer'],
            [['coupon_id'], 'exist', 'skipOnError' => false, 'targetClass' => Coupons::className(), 'targetAttribute' => ['coupon_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['tariff_assignment_hash_id'], 'exist', 'skipOnError' => false, 'targetClass' => TariffAssignment::className(), 'targetAttribute' => ['tariff_assignment_hash_id' => 'hash_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coupon_id' => \Yii::t('frontend', 'Type'),
            'date_use' => \Yii::t('frontend', 'Date'),
            'user_id' => \Yii::t('frontend', 'User'),
            'tariff_assignment_hash_id' => \Yii::t('frontend', 'Tariff'),
            'sum' => \Yii::t('frontend', 'Sum'),
        ];
    }

    /**
     * Gets query for [[Coupon]].
     *
     * @return ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupons::className(), ['id' => 'coupon_id']);
    }

    /**
     * Gets query for [[TariffAssignment]].
     *
     * @return ActiveQuery
     */
    public function getTariffAssignment()
    {
        return $this->hasOne(TariffAssignment::className(), ['hash_id' => 'tariff_assignment_hash_id']);
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
}
