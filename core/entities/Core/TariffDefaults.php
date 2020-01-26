<?php

namespace core\entities\Core;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariff_defaults".
 *
 * @property int $id
 * @property int|null $mb_limit
 * @property int|null $quantity_incoming_traffic
 * @property int|null $quantity_outgoing_traffic
 * @property string $file_path
 * @property int $ip_quantity
 * @property int $type
 * @property int $extend_days
 * @property int $extend_hours
 * @property int $extend_minutes
 * @property int $tariff_id
 */
class TariffDefaults extends ActiveRecord
{
    const TYPE_SIMPLE = 1;
    const TYPE_TRIAL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tariff_defaults';
    }

    public function getFiles(): array
    {
        return array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->file_path), array('')));
    }

    public static function create($mb_limit, $quantity_incoming_traffic, $quantity_outgoing_traffic,
                                  $file_path, $ip_quantity, $type, $extend_days, $extend_hours, $extend_minutes): self
    {
        $tariff = new static();
        $tariff->mb_limit = $mb_limit;
        $tariff->quantity_incoming_traffic = $quantity_incoming_traffic;
        $tariff->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $tariff->file_path = $file_path;
        $tariff->ip_quantity = $ip_quantity;
        $tariff->type = $type;
        $tariff->extend_days = $extend_days;
        $tariff->extend_hours = $extend_hours;
        $tariff->extend_minutes = $extend_minutes;
        return $tariff;
    }

    public function edit($mb_limit, $quantity_incoming_traffic, $quantity_outgoing_traffic,
                         $file_path, $ip_quantity, $type, $extend_days, $extend_hours, $extend_minutes): void
    {
        $this->mb_limit = $mb_limit;
        $this->quantity_incoming_traffic = $quantity_incoming_traffic;
        $this->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $this->file_path = $file_path;
        $this->ip_quantity = $ip_quantity;
        $this->type = $type;
        $this->extend_days = $extend_days;
        $this->extend_hours = $extend_hours;
        $this->extend_minutes = $extend_minutes;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic',
                'ip_quantity', 'type', 'extend_days', 'extend_hours', 'extend_minutes'], 'integer'],
            [['file_path'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mb_limit' => 'Ограничение по траффику',
            'quantity_incoming_traffic' => 'Количество потоков входящего трафика',
            'quantity_outgoing_traffic' => 'Количество потоков исходящего трафика',
            'ip_quantity' => 'Количество доступных ip',
            'file_path' => 'Пути к конфиг-файлам',
            'type' => 'Тип',
            'extend_days' => 'Продлить на (дней)',
            'extend_hours' => 'Продлить на (часов)',
            'extend_minutes' => 'Продлить на (минут)',
        ];
    }
}
