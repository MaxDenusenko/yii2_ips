<?php


namespace core\entities\Core\queries;


use core\entities\Core\Tariff;
use yii\db\ActiveQuery;

class TariffQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Tariff::STATUS_ACTIVE]);
    }

    public function draft()
    {
        return $this->andWhere(['status' => Tariff::STATUS_DRAFT]);
    }
}
