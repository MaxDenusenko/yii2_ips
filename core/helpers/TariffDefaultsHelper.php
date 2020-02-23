<?php


namespace core\helpers;


use core\entities\Core\TariffDefaults;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TariffDefaultsHelper
{
    public static function statusList(): array
    {
        return [
            TariffDefaults::TYPE_SIMPLE => \Yii::t('frontend', 'Simple'),
            TariffDefaults::TYPE_TRIAL => \Yii::t('frontend', 'Trial'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case TariffDefaults::TYPE_SIMPLE:
                $class = 'label label-default';
                break;
            case TariffDefaults::TYPE_TRIAL:
                $class = 'label label-info';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}
