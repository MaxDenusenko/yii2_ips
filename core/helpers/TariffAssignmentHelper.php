<?php


namespace core\helpers;


use core\entities\Core\TariffAssignment;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TariffAssignmentHelper
{
    public static function statusList(): array
    {
        return [
            TariffAssignment::STATUS_DRAFT => 'Неактивен',
            TariffAssignment::STATUS_ACTIVE => 'Активен',
            TariffAssignment::STATUS_REQUEST_TRIAL => 'Запрос триала',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case TariffAssignment::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case TariffAssignment::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case TariffAssignment::STATUS_REQUEST_TRIAL:
                $class = 'label label-info';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function createConfigString(TariffAssignment $tA, string $ip): string
    {
        return "{$tA->user->username}-{$tA->tariff_id}-{$tA->user_id};{$ip};{$tA->mb_limit}M;{$tA->quantity_incoming_traffic}/{$tA->quantity_outgoing_traffic};{$tA->date_to} {$tA->time_to};\r\n";
    }

    public static function checkFileHelper(string $help_file_path, $time = 10, $curTime = 0): bool
    {
        if (file_exists($help_file_path)){

            if ($curTime <= $time) {

                sleep(2);
                $curTime += 2;

                TariffAssignmentHelper::checkFileHelper($help_file_path, $time, $curTime);
            } else {
                return false;
            }
        } else {
            return true;
        }

        return false;
    }

    public static function updateFileInfo(string $file_path, TariffAssignment $tariffAssignment, array $arrIps, bool $add = false): void
    {
        $path_parts = pathinfo($file_path);
        $help_file_path = $path_parts['dirname'].DIRECTORY_SEPARATOR.$path_parts['filename'].'.tmp';

        if (!TariffAssignmentHelper::checkFileHelper($help_file_path))
            throw new \RuntimeException('Не удалось обновить файл конфигурации');

        $reading = fopen($file_path, 'r');
        $writing = fopen($help_file_path, 'w');

        while (!feof($reading)) {
            $line = fgets($reading);

            if (strpos($line, "{$tariffAssignment->tariff_id}-{$tariffAssignment->user_id}") !== false) {

                preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $line, $ip_match);
                $ip = $ip_match[0];

                if (($key = array_search($ip, $arrIps)) !== false) {
                    unset($arrIps[$key]);
                    $line = self::createConfigString($tariffAssignment, $ip);
                } else {
                    $line = false;
                }
            }

            if ($line !== false)
                fputs($writing, $line);

        }

        if (count($arrIps)) {
            foreach ($arrIps as $ip) {
                fputs($writing, self::createConfigString($tariffAssignment, $ip));
            }
        }

        fclose($reading); fclose($writing);

        unlink($file_path);
        rename($help_file_path, $file_path);
    }

    public static function deleteFileInfo(string $file_path, TariffAssignment $tariffAssignment)
    {
        $path_parts = pathinfo($file_path);
        $help_file_path = $path_parts['dirname'].DIRECTORY_SEPARATOR.$path_parts['filename'].'.tmp';

        if (!TariffAssignmentHelper::checkFileHelper($help_file_path))
            throw new \RuntimeException('Не удалось обновить файл конфигурации');

        $reading = fopen($file_path, 'r');
        $writing = fopen($help_file_path, 'w');

        while (!feof($reading)) {
            $line = fgets($reading);

            if (strpos($line, "{$tariffAssignment->tariff_id}-{$tariffAssignment->user_id}") === false)
                fputs($writing, $line);
        }
        fclose($reading); fclose($writing);

        unlink($file_path);
        rename($help_file_path, $file_path);
    }

    /**
     * @param $tariff_assignments TariffAssignment[]
     * @return array
     */
    public static function checkDateTariff($tariff_assignments)
    {
        $result = [];
        foreach ($tariff_assignments as $tariff_assignment) {

            if (!$tariff_assignment->isActive() || !$tariff_assignment->date_to || !$tariff_assignment->time_to)
                continue;

            if (("{$tariff_assignment->date_to} {$tariff_assignment->time_to}") < date("yy-m-d H:i"))
                continue;

            if (date("yy-m-d H:i",strtotime('+3 day',time())) > ("{$tariff_assignment->date_to} {$tariff_assignment->time_to}")) {
                $result []= "<p><strong>Tariff #3</strong> Окончание работы тарифа {$tariff_assignment->date_to} {$tariff_assignment->time_to}</p>";
            }
        }
        return $result;
    }
}
