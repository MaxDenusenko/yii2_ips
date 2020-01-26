<?php


namespace core\forms\manage\Core;


use core\entities\Core\TariffDefaults;
use yii\base\Model;

class TariffAssignmentFormEditRenewal extends Model
{
    public $extend_days;
    public $extend_hours;
    public $extend_minutes;

    public function __construct(TariffDefaults $default = null, $config = [])
    {
        if ($default) {
            $this->extend_days = $default->extend_days;
            $this->extend_hours = $default->extend_hours;
            $this->extend_minutes = $default->extend_minutes;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [[ 'extend_days', 'extend_hours', 'extend_minutes'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'extend_days' => 'Продлить на (дней)',
            'extend_hours' => 'Продлить на (часов)',
            'extend_minutes' => 'Продлить на (минут)',
        ];
    }
}
