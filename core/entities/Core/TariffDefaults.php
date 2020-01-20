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
 * @property string $name
 */
class TariffDefaults extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tariff_defaults';
    }

    public static function create($mb_limit, $quantity_incoming_traffic, $quantity_outgoing_traffic, $name): self
    {
        $tariff = new static();
        $tariff->mb_limit = $mb_limit;
        $tariff->quantity_incoming_traffic = $quantity_incoming_traffic;
        $tariff->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $tariff->name = $name;
        return $tariff;
    }

    public function edit($mb_limit, $quantity_incoming_traffic, $quantity_outgoing_traffic, $name)
    {
        $this->mb_limit = $mb_limit;
        $this->quantity_incoming_traffic = $quantity_incoming_traffic;
        $this->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mb_limit', 'quantity_incoming_traffic', 'quantity_outgoing_traffic'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
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
            'name' => 'Название',
        ];
    }
}
