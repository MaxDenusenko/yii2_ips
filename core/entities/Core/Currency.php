<?php

namespace core\entities\Core;

use core\entities\Core\queries\CurrencyQuery;
use DomainException;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "currencies".
 *
 * @property int $id
 * @property string $code
 * @property int $active
 * @property int $base
 * @property string $symbol
 */
class Currency extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_BASE = 1;
    const STATUS_NOT_BASE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%currencies}}';
    }

    /**
     * @param string $code
     * @param string $symbol
     * @return static
     */
    public static function create(string $code, string $symbol)
    {
        $currency = new static();
        $currency->code = $code;
        $currency->symbol = $symbol;
        return $currency;
    }

    public function edit(?string $code, string $symbol)
    {
        $this->code = $code;
        $this->symbol = $symbol;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'base'], 'integer'],
            [['code', 'symbol'], 'required'],
            [['code', 'symbol'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'active' => 'Активен',
            'base' => 'Базовая валюта',
            'symbol' => 'Символ',
        ];
    }

    public function isActive(): bool
    {
        return $this->active == self::STATUS_ACTIVE;
    }

    public function isBase(): bool
    {
        return $this->base == self::STATUS_BASE;
    }

    public function isInactive(): bool
    {
        return $this->active == self::STATUS_INACTIVE;
    }

    public function deactivate()
    {
        $this->active = self::STATUS_INACTIVE;
    }

    public function activate()
    {
        if ($this->isActive()) {
            throw new DomainException('Currency is already active.');
        }
        $this->active = self::STATUS_ACTIVE;
    }

    public function setBase()
    {
        if ($this->isBase()) {
            throw new DomainException('Currency is already base.');
        }
        $this->base = self::STATUS_BASE;
    }

    public function inBase()
    {
        $this->base = self::STATUS_NOT_BASE;
    }

    public static function find(): CurrencyQuery
    {
        return new CurrencyQuery(static::class);
    }
}
