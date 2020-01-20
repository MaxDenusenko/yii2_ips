<?php


namespace core\repositories\Core;


use core\entities\Core\TariffAssignment;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class TariffAssignmentRepository
{
    /**
     * @param $tariff_id
     * @param $user_id
     * @return TariffAssignment
     */
    public function get($tariff_id, $user_id): TariffAssignment
    {
        if (!$tariff = TariffAssignment::findOne([$tariff_id, $user_id])) {
            throw new NotFoundException('Tariff is not found');
        }
        return $tariff;
    }

    /**
     * @param TariffAssignment $tariff
     */
    public function save(TariffAssignment $tariff): void
    {
        if (!$tariff->save()) {
            throw new \RuntimeException('Saving error');
        }
    }

    /**
     * @param TariffAssignment $tariff
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove(TariffAssignment $tariff): void
    {
        if (!$tariff->delete()) {
            throw new \RuntimeException('Removing error');
        }
    }
}
