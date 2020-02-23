<?php


namespace console\controllers;


use core\entities\Core\Order;
use core\entities\Core\TariffAssignment;
use core\services\manage\Core\OrderManageService;
use core\services\manage\Core\TariffAssignmentManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class TariffController extends Controller
{
    private $tariffs;
    private $orders;

    public function __construct($id, $module,
                                TariffAssignmentManageService $tariffs,
                                OrderManageService $orders,
                                $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->tariffs = $tariffs;
        $this->orders = $orders;
    }

    private function checkTariffTime()
    {
        $tariffs = TariffAssignment::find()->active()->all();

        if (empty($tariffs)) {
            $this->stdout("{$this->ansiFormat('Активных тарифов нет', Console::FG_YELLOW)}\n", Console::BOLD);
            return 0;
        }

        try {
            /** @var TariffAssignment $tariff */
            foreach ($tariffs as $tariff) {

                if (!($tariff->time_left > 0)) {
                    $this->tariffs->deactivated($tariff->tariff_id, $tariff->user_id, $tariff->hash_id);
                } else {
                    $tariff->time_left -= 1;
                    $tariff->save();
                }

            }
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            $this->stdout("{$this->ansiFormat($e->getMessage(), Console::FG_RED)}\n", Console::BOLD);
            return 1;
        }

        $this->stdout("{$this->ansiFormat('Проверка прошла успешно', Console::FG_YELLOW)}\n", Console::BOLD);
        return 0;
    }

    private function checkOrderTime()
    {
        $orders = Order::find()->active()->all();

        if (empty($orders)) {
            $this->stdout("{$this->ansiFormat('Активных заказов нет', Console::FG_YELLOW)}\n", Console::BOLD);
            return 0;
        }

        try {
            /** @var Order[] $orders */
            foreach ($orders as $order) {

                if (strlen($order->time_left) > 0 && $order->time_left <= 0) {
                    $order->time_left = null;
                    $order->canceled();
                    $order->save();
                } else if (strlen($order->time_left) > 0) {
                    $order->time_left -= 1;
                    $order->save();
                }
            }
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            $this->stdout("{$this->ansiFormat($e->getMessage(), Console::FG_RED)}\n", Console::BOLD);
            return 1;
        }

        $this->stdout("{$this->ansiFormat('Проверка прошла успешно', Console::FG_YELLOW)}\n", Console::BOLD);
        return 0;
    }

    public function actionCheckTime()
    {
        $checkTariffTimeResult = $this->checkTariffTime();
        $checkOrderTimeResult = $this->checkOrderTime();

        return $checkTariffTimeResult == 0 && $checkOrderTimeResult == 0 ? 0 : 1;
    }
}
