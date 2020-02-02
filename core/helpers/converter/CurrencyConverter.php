<?php


namespace core\helpers\converter;


use Yii;

class CurrencyConverter extends \imanilchaudhari\CurrencyConverter\CurrencyConverter
{
    /**
     * @inheritdoc
     */
    public function getRateProvider()
    {
        if (!$this->rateProvider) {
            $this->setRateProvider(new OpenExchangeRatesApi([
                'appId' => Yii::$app->params['openExchangeRate']['appId'],
            ]));
        }

        return $this->rateProvider;
    }
}
