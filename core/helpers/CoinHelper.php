<?php


namespace core\helpers;


use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;
use core\entities\CoinPay;
use core\entities\Core\Order;
use Yii;

class CoinHelper
{
    public function createPayData(Order $order)
    {
        ApiClient::init(Yii::$app->params['coin_commerce']['api_key']);

        $orderItem = $order->orderItems[0];

        $checkoutObj = new Checkout([
            "description" => 'Оплата тарифа',
            "local_price" => [
                "amount" => $orderItem->price,
                "currency" => "USD"
            ],
            "name" => $orderItem->name,
            "pricing_type" => "fixed_price",
            "requested_info" => []
        ]);

        try {
            $checkoutObj->save();

            $pay_data = new CoinPay();
            $pay_data->order_id = $order->id;
            $pay_data->status = CoinPay::STATUS_NOT_PAID;
            $pay_data->identity = $checkoutObj->id;
            $pay_data->pay_link = "https://commerce.coinbase.com/checkout/{$checkoutObj->id}";
            $pay_data->save();

        } catch (\Exception $exception) {
            throw new \Exception("Не удалось создать заказ". $exception->getMessage());
        }
    }

    public function getPayLink(Order $order) {

        $pay_data = CoinPay::find()->where(['order_id' => $order->id])->one();
        if ($pay_data) {
            return $pay_data->pay_link;
        }

        return false;
    }
}
