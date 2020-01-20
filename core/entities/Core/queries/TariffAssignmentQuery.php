<?php


namespace core\entities\Core\queries;


use core\entities\Core\TariffAssignment;
use yii\db\ActiveQuery;

class TariffAssignmentQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => TariffAssignment::STATUS_ACTIVE]);
    }

    public function draft()
    {
        return $this->andWhere(['status' => TariffAssignment::STATUS_DRAFT]);
    }
}
