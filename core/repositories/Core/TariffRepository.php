<?php


namespace core\repositories\Core;


use core\entities\Core\Tariff;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class TariffRepository
{
    /**
     * @param $id
     * @return Tariff
     */
    public function get($id): Tariff
    {
        if (!$tariff = Tariff::findOne($id)) {
            throw new NotFoundException(\Yii::t('frontend', 'Tariff not found'));
        }
        return $tariff;
    }

    /**
     * @param Tariff $tariff
     */
    public function save(Tariff $tariff): void
    {
        if (!$tariff->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error'));
        }
    }

    /**
     * @param Tariff $tariff
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(Tariff $tariff): void
    {
        if (!$tariff->delete()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Removing error'));
        }
    }

    public function existsByMainCategory($id): bool
    {
        return Tariff::find()->andWhere(['category_id' => $id])->exists();
    }
}
