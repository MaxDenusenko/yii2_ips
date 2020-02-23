<?php


namespace core\forms\manage\Core;


use core\entities\Core\Currency;
use yii\base\Model;

class CurrencyForm extends Model
{
    public $code;
    public $symbol;

    private $_currency;

    public function __construct(Currency $currency = null, $config = [])
    {
        if ($currency) {
            $this->code = $currency->code;
            $this->symbol = $currency->symbol;

            $this->_currency = $currency;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['code', 'symbol'], 'required'],
            [['code', 'symbol'], 'string'],
            [['code'], 'unique', 'targetClass' => Currency::class, 'filter' => $this->_currency ? ['<>', 'id', $this->_currency->id] : null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => \Yii::t('frontend', 'Code'),
            'symbol' => \Yii::t('frontend', 'Symbol'),
        ];
    }
}
