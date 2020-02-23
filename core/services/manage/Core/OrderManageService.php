<?php


namespace core\services\manage\Core;


use core\entities\Core\Coupons;
use core\entities\Core\Order;
use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use core\forms\manage\Core\AdditionalIpOrderForm;
use core\forms\manage\Core\OrderForm;
use core\forms\manage\Core\RenewalForm;
use core\repositories\Core\OrderRepository;
use core\services\cabinet\ProfileService;
use core\services\PayService;
use core\services\TransactionManager;
use Yii;

class OrderManageService
{
    private $orders;
    private $transaction;
    private $profileService;
    private $payService;

    public function __construct(
        OrderRepository $orders,
        TransactionManager $transaction,
        ProfileService $profileService,
        PayService $payService
    )
    {
        $this->orders = $orders;
        $this->transaction = $transaction;
        $this->profileService = $profileService;
        $this->payService = $payService;
    }

    /**
     * @param OrderForm $form
     * @param null $user_id
     * @return Order
     */
    public function create(OrderForm $form, $user_id = null): Order
    {
        $order = Order::create(
            $form->payment_method_id,
            $form->comment,
            $form->product->cost,
            $user_id
        );

        $order->addItem($form->product);

        $this->transaction->wrap(function () use ($order, $form, $user_id) {

            $tariff = Tariff::findOne($form->product->product_id);
            $user = User::findOne($user_id);

            $this->orders->save($order);
            $this->profileService->addTariff($user, $tariff, $form->trial, $order->orderItems[0], $form->additional_id);

            $tariffAssignment = $order->orderItems[0]->tariffAssignment;

            $tariffAssignment->setCoupon($form->coupon_code);
            $tariffAssignment->save();

            $price = $tariffAssignment->getPrice();

            $order->amount = $price;
            $order->type = Order::TYPE_TARIFF_PAI;
            $this->orders->save($order);

            $orderItems = $order->orderItems[0];
            $orderItems->price = $price;
            $orderItems->save();

            $this->payService->createPayData($order);

            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'You ordered a tariff').' '.$tariff->name);
        });

        return $order;
    }

    public function createRenewal(RenewalForm $form, $user_id)
    {
        $order = Order::create(
            $form->payment_method_id,
            $form->comment,
            $form->assignment->cost,
            $user_id
        );

        $order->addRenewalItem($form->assignment);

        $this->transaction->wrap(function () use ($order, $form, $user_id) {

            $tariff = Tariff::findOne($form->assignment->product_id);

            $this->orders->save($order);

            $tariffAssignment = $order->renewalOrderItems[0]->tariffAssignment;
            $price = $tariffAssignment->getPrice(true , false, $form->renew_with_additional_ip ? false : true);

            $order->amount = $price;
            $order->type = Order::TYPE_TARIFF_RENEWAL;
            $this->orders->save($order);

            $orderItems = $order->renewalOrderItems[0];
            $orderItems->price = $price;
            $orderItems->save();

            $this->payService->createPayData($order, true);

            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'You ordered a tariff extension').' '.$tariff->name);
        });

        return $order;
    }

    public function getPriceAdditionalIP(TariffAssignment $tariffAssignment,int $additional_ip)
    {
        $tariff_time_min = $tariffAssignment->getDefaultMin();

        if (!$tariff_time_min) {
            throw new \Exception('Error');
        }

        $price = ($tariffAssignment->getTimeLeft()/$tariff_time_min)*$tariffAssignment->getPrice(false, false, true)*$tariffAssignment->tariff->price_for_additional_ip/100;
        $price = round($price, 2);
        $price = $price * $additional_ip;

        return $price;
    }

    /**
     * @param AdditionalIpOrderForm $form
     * @param $user_id
     * @return Order
     * @throws \Exception
     */
    public function createAdditionalIpRequest(AdditionalIpOrderForm $form, $user_id)
    {
        $tariffAssignment = TariffAssignment::find()->active()->where(['hash_id' => $form->assignment->product_hash])->one();

        $price = $this->getPriceAdditionalIP($tariffAssignment, $form->additional_ip);

        $order = Order::create(
            $form->payment_method_id,
            $form->comment,
            $price,
            $user_id
        );

        $order->time_left = 60;
        $order->addAdditionalIdItem($form->assignment, $form->additional_ip, $price);

        $this->transaction->wrap(function () use ($order, $form, $user_id) {

            $tariff = Tariff::findOne($form->assignment->product_id);

            $order->type = Order::TYPE_TARIFF_ADDITIONAL_IP;
            $this->orders->save($order);
            $this->payService->createPayData($order, false, true);

            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'You ordered')." {$form->additional_ip} ".\Yii::t('frontend', 'for tariff')." ".$tariff->name);
        });

        return $order;
    }
}
