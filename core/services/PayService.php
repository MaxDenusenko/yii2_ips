<?php


namespace core\services;


use core\entities\Core\Order;

class PayService
{
    private function pay_actions()
    {
        return [
            'coin' => 'core\helpers\CoinHelper'
        ];
    }

    public function createPayData(Order $order, $renewal = false, $additional_ip = false): void
    {
        $pay_method_code = $order->paymentMethod->name;
        $className = $this->pay_actions()[$pay_method_code];

        $payHelper = new $className;
        $payHelper->createPayData($order, $renewal, $additional_ip);
    }

    public function getPayLink(Order $order): string
    {
        $pay_method_code = $order->paymentMethod->name;
        $className = $this->pay_actions()[$pay_method_code];

        $payHelper = new $className;
        return $payHelper->getPayLink($order);
    }

    public function getPaiStatus(Order $order)
    {
        $pay_method_code = $order->paymentMethod->name;
        $className = $this->pay_actions()[$pay_method_code];

        $payHelper = new $className;
        return $payHelper->getStatus($order);
    }
}
