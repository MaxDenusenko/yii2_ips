<?php


namespace core\entities\Core\queries;


use core\entities\Core\Order;
use yii\db\ActiveQuery;

class OrderQuery extends ActiveQuery
{
    public function notCancel()
    {
        return $this->andWhere(['!=', 'status',  Order::STATUS_CANCELED]);
    }

    public function active()
    {
        return $this->andWhere(['status' => Order::STATUS_ACTIVE]);
    }
}
