<?php


namespace core\helpers;


use core\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    /**
     * @return array
     */
    public static function statusList(): array
    {
        return [
            User::STATUS_ACTIVE => \Yii::t('frontend', 'Active'),
            User::STATUS_INACTIVE => \Yii::t('frontend', 'Inactive'),
            User::STATUS_DELETED => \Yii::t('frontend', 'Deleted'),
            User::STATUS_BANNED => \Yii::t('frontend', 'Banned'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_INACTIVE:
                $class = 'label label-default';
                break;
            case User::STATUS_DELETED:
                $class = 'label label-error';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case User::STATUS_BANNED:
                $class = 'label label-warning';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class
        ]);
    }
}
