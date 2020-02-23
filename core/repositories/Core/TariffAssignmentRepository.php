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
     * @param $hash_id
     * @return TariffAssignment
     */
    public function get($tariff_id, $user_id, $hash_id): TariffAssignment
    {
        if (!$tariff = TariffAssignment::findOne(['tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id])) {
            throw new NotFoundException(\Yii::t('frontend', 'Tariff not found'));
        }
        return $tariff;
    }

    /**
     * @param TariffAssignment $tariff
     */
    public function save(TariffAssignment $tariff): void
    {
        if (!$tariff->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error'));
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
            throw new \RuntimeException(\Yii::t('frontend', 'Removing error'));
        }
    }
}
