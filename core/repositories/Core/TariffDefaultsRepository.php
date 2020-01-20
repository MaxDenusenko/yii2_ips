<?php


namespace core\repositories\Core;


use core\entities\Core\TariffDefaults;
use core\repositories\NotFoundException;

class TariffDefaultsRepository
{
    /**
     * @param $id
     * @return TariffDefaults
     */
    public function get($id): TariffDefaults
    {
        if (!$tariff = TariffDefaults::findOne($id)) {
            throw new NotFoundException('Tariff is not found');
        }
        return $tariff;
    }

    /**
     * @param TariffDefaults $tariff
     */
    public function save(TariffDefaults $tariff): void
    {
        if (!$tariff->save()) {
            throw new \RuntimeException('Saving error');
        }
    }

    /**
     * @param TariffDefaults $tariff
     * @throws \Throwable
     */
    public function remove(TariffDefaults $tariff): void
    {
        if (!$tariff->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }
}