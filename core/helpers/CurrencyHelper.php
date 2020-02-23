<?php


namespace core\helpers;


use core\entities\Core\Currency;
use core\repositories\NotFoundException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CurrencyHelper
{
    public static function statusList(): array
    {
        return [
            Currency::STATUS_ACTIVE => \Yii::t('frontend', 'Active'),
            Currency::STATUS_INACTIVE => \Yii::t('frontend', 'Inactive'),
        ];
    }

    public static function baseList(): array
    {
        return [
            Currency::STATUS_BASE => \Yii::t('frontend', 'Yes'),
            Currency::STATUS_NOT_BASE => \Yii::t('frontend', 'Not'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function baseStatus($status): string
    {
        return ArrayHelper::getValue(self::baseList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Currency::STATUS_INACTIVE:
                $class = 'label label-default';
                break;
            case Currency::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function baseLabel($base_status): string
    {
        switch ($base_status) {
            case Currency::STATUS_NOT_BASE:
                $class = 'label label-default';
                break;
            case Currency::STATUS_BASE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::baseList(), $base_status), [
            'class' => $class,
        ]);
    }

    public static function currencyList()
    {
        return [
            'USD' => 'USD',
            'RUB' => 'RUB'
        ];
    }

    /**
     * @return mixed
     */
    public static function getBaseSymbol()
    {
        if (!$currency = Currency::find()->base()->one()) {
            throw new NotFoundException(\Yii::t('frontend', 'Base currency not set!'));
        }
        return $currency->symbol;
    }

    /**
     * @return mixed
     */
    public static function getBaseCode()
    {
        if (!$currency = Currency::find()->base()->one()) {
            throw new NotFoundException(\Yii::t('frontend', 'Base currency not set!'));
        }
        return $currency->code;
    }

    /**
     * @return mixed
     */
    public static function getActiveSymbol()
    {
        if (!$currency = Currency::find()->active()->one()) {
            throw new NotFoundException(\Yii::t('frontend', 'No active currency set!'));
        }
        return $currency->symbol;
    }

    /**
     * @return mixed
     */
    public static function getActiveCode()
    {
        if (!$currency = Currency::find()->active()->one()) {
            throw new NotFoundException(\Yii::t('frontend', 'No active currency set!'));
        }
        return $currency->code;
    }
}
