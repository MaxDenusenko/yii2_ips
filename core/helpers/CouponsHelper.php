<?php


namespace core\helpers;


use core\entities\Core\Coupons;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CouponsHelper
{
    public static function typeList(): array
    {
        return [
            Coupons::TYPE_ONLY_PAI => \Yii::t('frontend', 'Tariff payment'),
            Coupons::TYPE_PAI_AND_RENEWAL => \Yii::t('frontend', 'Payment and renewal'),
        ];
    }

    public static function typeName($type): string
    {
        return ArrayHelper::getValue(self::typeList(), $type);
    }

    public static function typeLabel($type): string
    {
        switch ($type) {
            case Coupons::TYPE_ONLY_PAI:
                $class = 'label label-default';
                break;
            case Coupons::TYPE_PAI_AND_RENEWAL:
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::typeList(), $type), [
            'class' => $class,
        ]);
    }
}
