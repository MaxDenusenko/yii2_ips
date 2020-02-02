<?php


namespace core\services\manage\Core;


use core\entities\Core\Order;
use core\entities\Core\Tariff;
use core\entities\User\User;
use core\forms\manage\Core\OrderForm;
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
            $this->profileService->addTariff($user, $tariff, false, $order->orderItems[0]);
            $this->payService->createPayData($order);

            Yii::$app->session->setFlash('success', 'Вы заказали тариф '.$tariff->name);
        });

        return $order;
    }
}
