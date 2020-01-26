<?php


namespace console\controllers;


use core\entities\Core\TariffAssignment;
use core\services\manage\Core\TariffAssignmentManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class TariffController extends Controller
{
    private $tariffs;

    public function __construct($id, $module, TariffAssignmentManageService $tariffs, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->tariffs = $tariffs;
    }

    public function actionCheckTime()
    {
        $tariffs = TariffAssignment::find()->active()->all();

        if (empty($tariffs)) {
            $this->stdout("{$this->ansiFormat('Активных тарифов нет', Console::FG_YELLOW)}\n", Console::BOLD);
            return 0;
        }

        try {
            /** @var TariffAssignment $tariff */
            foreach ($tariffs as $tariff) {

                if (( !$tariff->date_to || !$tariff->time_to ) || (("{$tariff->date_to} {$tariff->time_to}") < date("yy-m-d H:i"))) {
                    $this->tariffs->deactivated($tariff->tariff_id, $tariff->user_id);
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
}
