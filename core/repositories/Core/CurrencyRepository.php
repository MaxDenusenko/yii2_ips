<?php


namespace core\repositories\Core;


use core\entities\Core\Currency;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class CurrencyRepository
{
    /**
     * @param $id
     * @return Currency
     */
    public function get($id): Currency
    {
        if (!$currency = Currency::findOne($id)) {
            throw new NotFoundException(\Yii::t('frontend', 'Currency is not found'));
        }
        return $currency;
    }

    /**
     * @param Currency $currency
     */
    public function save(Currency $currency): void
    {
        if (!$currency->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error'));
        }
    }

    /**
     * @param Currency $currency
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(Currency $currency): void
    {
        if (!$currency->delete()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Removing error'));
        }
    }
}
