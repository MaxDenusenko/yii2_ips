<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "coupons".
 *
 * @property int $id
 * @property int|null $number
 * @property string $code
 * @property int $per_cent
 * @property int $type
 */
class Coupons extends ActiveRecord
{
    const TYPE_ONLY_PAI = 1;
    const TYPE_PAI_AND_RENEWAL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%coupons}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'per_cent', 'type'], 'integer'],
            [['code', 'per_cent', 'type'], 'required'],
            [['code'], 'string', 'max' => 255],
            [['code'] , 'unique', 'targetClass' => Coupons::class],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => \Yii::t('frontend', 'Number'),
            'code' => \Yii::t('frontend', 'Code'),
            'per_cent' => \Yii::t('frontend', 'Per Cent'),
            'type' => \Yii::t('frontend', 'Type'),
        ];
    }
}
