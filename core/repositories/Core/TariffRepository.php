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
            throw new NotFoundException('Tariff is not found');
        }
        return $tariff;
    }

    /**
     * @param Tariff $tariff
     */
    public function save(Tariff $tariff): void
    {
        if (!$tariff->save()) {
            throw new \RuntimeException('Saving error');
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
            throw new \RuntimeException('Removing error');
        }
    }
}
