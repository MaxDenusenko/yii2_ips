<?php


namespace core\entities\Core\queries;


use core\entities\Core\Currency;
use yii\db\ActiveQuery;

class CurrencyQuery extends ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['active' => Currency::STATUS_ACTIVE]);
    }

    public function base()
    {
        return $this->andWhere(['base' => Currency::STATUS_BASE]);
    }

    public function main()
    {
        return $this->active()->base();
    }
}
