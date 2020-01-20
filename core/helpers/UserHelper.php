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
            User::STATUS_ACTIVE => 'Активный',
            User::STATUS_INACTIVE => 'Неактивный',
            User::STATUS_DELETED => 'Удален',
            User::STATUS_BANNED => 'Забанен',
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
