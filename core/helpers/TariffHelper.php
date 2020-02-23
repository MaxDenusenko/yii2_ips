<?php


namespace core\helpers;


use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TariffHelper
{
    public static function statusList(): array
    {
        return [
            Tariff::STATUS_DRAFT => \Yii::t('frontend', 'Inactive'),
            Tariff::STATUS_ACTIVE => \Yii::t('frontend', 'Active'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Tariff::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Tariff::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function categoryList()
    {
        return ArrayHelper::map(CategoryTariffs::find()->joinWith(['translation'])->orderBy('name')->asArray()->all(), 'id', 'translation.name');
    }
}
