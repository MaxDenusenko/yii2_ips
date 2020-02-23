<?php


namespace core\repositories\Core;


use core\entities\Core\Order;
use core\repositories\NotFoundException;
use yii\db\StaleObjectException;

class OrderRepository
{
    /**
     * @param $id
     * @return Order
     */
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException(\Yii::t('frontend', 'Order is not found.'));
        }
        return $order;
    }

    /**
     * @param Order $order
     */
    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Saving error.'));
        }
    }

    /**
     * @param Order $order
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new \RuntimeException(\Yii::t('frontend', 'Removing error.'));
        }
    }
}
