<?php


namespace core\helpers;


use core\entities\Core\Order;
use core\entities\Core\PaymentMethod;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderHelper
{
    public static function statusList(): array
    {
        return [
            Order::STATUS_ACTIVE => \Yii::t('frontend', 'Not paid'),
            Order::STATUS_PAID => \Yii::t('frontend', 'Paid'),
            Order::STATUS_CANCELED => \Yii::t('frontend', 'Not relevant'),
        ];
    }

    public static function typeList(): array
    {
        return [
            Order::TYPE_TARIFF_PAI => \Yii::t('frontend', 'Tariff payment'),
            Order::TYPE_TARIFF_RENEWAL => \Yii::t('frontend', 'Tariff extension'),
            Order::TYPE_TARIFF_ADDITIONAL_IP => \Yii::t('frontend', 'Buying extra. Ip'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function typeName($type): string
    {
        return ArrayHelper::getValue(self::typeList(), $type);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Order::STATUS_ACTIVE:
                $class = 'label label-default';
                break;
            case Order::STATUS_PAID:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function typeLabel($status): string
    {
        switch ($status) {
            case Order::TYPE_TARIFF_PAI:
                $class = 'label label-default';
                break;
            case Order::TYPE_TARIFF_RENEWAL:
                $class = 'label label-success';
            case Order::TYPE_TARIFF_ADDITIONAL_IP:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::typeList(), $status), [
            'class' => $class,
        ]);
    }

    public static function paymentList()
    {
        return ArrayHelper::map(PaymentMethod::find()->orderBy('label')->asArray()->all(), 'id', 'label');
    }
}
