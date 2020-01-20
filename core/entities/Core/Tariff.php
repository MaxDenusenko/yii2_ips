<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffQuery;
use core\entities\User\User;
use DomainException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariffs".
 *
 * @property int $id
 * @property int $number
 * @property string $name
 * @property int|null $quantity
 * @property int|null $price
 * @property int $status
 */
class Tariff extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;

    /**
     * @param $name
     * @param $number
     * @param $quantity
     * @param $price
     * @param $status
     * @return static
     */
    public static function create($name, $number, $quantity, $price, $status)
    {
        $tariff = new static();
        $tariff->name = $name;
        $tariff->number = $number;
        $tariff->quantity = $quantity;
        $tariff->price = $price;
        $tariff->status = $status;
        return $tariff;
    }

    /**
     * @param $name
     * @param $number
     * @param $quantity
     * @param $price
     */
    public function edit($name, $number, $quantity, $price)
    {
        $this->name = $name;
        $this->number = $number;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tariffs}}';
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Tariff is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Tariff is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'name'], 'required'],
            [['number', 'quantity', 'price', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'name' => 'Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'status' => 'Status',
        ];
    }

    public static function find(): TariffQuery
    {
        return new TariffQuery(static::class);
    }

//    public function getUserAssignments(): ActiveQuery
//    {
//        return $this->hasMany(TariffAssignment::class, ['tariff_id' => 'id']);
//    }
//
//    public function getUsers(): ActiveQuery
//    {
//        return $this->hasMany(User::class, ['id' => 'user_id'])->via('userAssignments');
//    }
}
