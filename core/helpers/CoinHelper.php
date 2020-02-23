<?php


namespace core\helpers;


use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;
use core\entities\CoinPay;
use core\entities\Core\Order;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use CoinbaseCommerce\Resources\Charge;

class CoinHelper
{
    public static function statusWebhookList(): array
    {
        return [
            CoinPay::WEBHOOK_CONFIRMED => \Yii::t('frontend', 'Payment confirmed'),
            CoinPay::WEBHOOK_DELAYED => \Yii::t('frontend', 'Payment confirmed'),
            CoinPay::WEBHOOK_CREATED => \Yii::t('frontend', 'Payment created'),
            CoinPay::WEBHOOK_FAILED => \Yii::t('frontend', 'Payment failed'),
            CoinPay::WEBHOOK_PENDING => \Yii::t('frontend', 'Payment detected but not confirmed'),
            CoinPay::WEBHOOK_RESOLVED => \Yii::t('frontend', 'Payment allowed'),
            CoinPay::PAI_RESOLVED => \Yii::t('frontend', 'Payment not created'),
        ];
    }

    public static function statusWebhookName($status): string
    {
        return ArrayHelper::getValue(self::statusWebhookList(), $status);
    }

    public static function statusWebhookLabel($status): string
    {
        switch ($status) {
            case CoinPay::WEBHOOK_CONFIRMED:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusWebhookList(), $status), [
            'class' => $class,
        ]);
    }

    public function getStatus(Order $order)
    {
        $pay_data = CoinPay::find()->where(['order_id' => $order->id])->one();
        if ($pay_data) {
            return $this->statusWebhookLabel($pay_data->status);
        }

        return false;
    }

    public function createPayData(Order $order, $renewal = false, $additional_ip = false)
    {
        ApiClient::init(Yii::$app->params['coin_commerce']['api_key']);

        if ($additional_ip) {

            $additionalOrderItem = $order->additionalOrderItems[0];

            $checkoutObj = new Checkout([
                "description" => \Yii::t('frontend', 'Order extra ip for tariff').' '.$additionalOrderItem->name,
                "local_price" => [
                    "amount" => $additionalOrderItem->price,
                    "currency" => $additionalOrderItem->currency
                ],
                "name" => $additionalOrderItem->name,
                "pricing_type" => "fixed_price",
                "requested_info" => []
            ]);

            try {
                $checkoutObj->save();

                $pay_data = new CoinPay();
                $pay_data->order_id = $order->id;
                $pay_data->identity = $checkoutObj->id;
                $pay_data->pay_link = "https://commerce.coinbase.com/checkout/{$checkoutObj->id}";

                if (!$pay_data->save()) {
                    throw new \Exception(\Yii::t('frontend', 'Failed to create order.'));
                }

            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }

        } else if ($renewal) {

            $renewalOrderItem = $order->renewalOrderItems[0];

            $checkoutObj = new Checkout([
                "description" => \Yii::t('frontend', 'Tariff extension'),
                "local_price" => [
                    "amount" => $renewalOrderItem->price,
                    "currency" => $renewalOrderItem->currency
                ],
                "name" => $renewalOrderItem->name,
                "pricing_type" => "fixed_price",
                "requested_info" => []
            ]);

            try {
                $checkoutObj->save();

                $pay_data = new CoinPay();
                $pay_data->order_id = $order->id;
                $pay_data->identity = $checkoutObj->id;
                $pay_data->pay_link = "https://commerce.coinbase.com/checkout/{$checkoutObj->id}";

                if (!$pay_data->save()) {
                    throw new \Exception(\Yii::t('frontend', 'Failed to create order.'));
                }

            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }

        } else {

            $orderItem = $order->orderItems[0];

            $checkoutObj = new Checkout([
                "description" => \Yii::t('frontend', 'Tariff payment'),
                "local_price" => [
                    "amount" => $orderItem->tariffAssignment->getPrice(),
                    "currency" => $orderItem->currency
                ],
                "name" => $orderItem->name,
                "pricing_type" => "fixed_price",
                "requested_info" => []
            ]);

            try {
                $checkoutObj->save();

                $pay_data = new CoinPay();
                $pay_data->order_id = $order->id;
                $pay_data->identity = $checkoutObj->id;
                $pay_data->pay_link = "https://commerce.coinbase.com/checkout/{$checkoutObj->id}";

                if (!$pay_data->save()) {
                    throw new \Exception(\Yii::t('frontend', 'Failed to create order.'));
                }

            } catch (\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
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
