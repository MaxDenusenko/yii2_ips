<?php


namespace core\entities\Core\queries;


use core\entities\Core\Tariff;
use omgdef\multilingual\MultilingualQuery;

class TariffQuery extends MultilingualQuery
{
    public function noCategory()
    {
        return $this->andWhere(['category_id' => null]);
    }

    public function active()
    {
        return $this->andWhere(['status' => Tariff::STATUS_ACTIVE]);
    }

    public function draft()
    {
        return $this->andWhere(['status' => Tariff::STATUS_DRAFT]);
    }
}
