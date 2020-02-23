<?php


namespace core\helpers;


use core\entities\Core\Coupons;
use core\entities\Core\TariffAssignment;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TariffAssignmentHelper
{
    public static function statusList(): array
    {
        return [
            TariffAssignment::STATUS_DRAFT => \Yii::t('frontend', 'Stopped'),
            TariffAssignment::STATUS_ACTIVE => \Yii::t('frontend', 'Active'),
            TariffAssignment::STATUS_REQUEST_TRIAL => \Yii::t('frontend', 'Trial request'),
            TariffAssignment::STATUS_DEACTIVATED => \Yii::t('frontend', 'Deactivated'),
            TariffAssignment::STATUS_REQUEST_RENEWAL => \Yii::t('frontend', 'Renewal Request'),
            TariffAssignment::STATUS_REQUEST_CANCEL => \Yii::t('frontend', 'Cancellation Request'),
            TariffAssignment::STATUS_CANCEL => \Yii::t('frontend', 'Canceled'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function frontStatusLabel($status)
    {
        switch ($status) {
            case TariffAssignment::STATUS_DRAFT:
            case TariffAssignment::STATUS_DEACTIVATED:
                $class = 'lime';
                break;
            case TariffAssignment::STATUS_ACTIVE:
                $class = 'teal darken-1';
                break;
            case TariffAssignment::STATUS_REQUEST_TRIAL:
            case TariffAssignment::STATUS_REQUEST_RENEWAL:
                $class = 'blue';
                break;
            case TariffAssignment::STATUS_REQUEST_CANCEL:
                $class = 'lime darken-1';
                break;
            case TariffAssignment::STATUS_CANCEL:
                $class = 'red';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => "new badge $class",
        ]);

    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case TariffAssignment::STATUS_DRAFT:
            case TariffAssignment::STATUS_DEACTIVATED:
                $class = 'label label-default';
                break;
            case TariffAssignment::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case TariffAssignment::STATUS_REQUEST_TRIAL:
            case TariffAssignment::STATUS_REQUEST_RENEWAL:
                $class = 'label label-info';
                break;
            case TariffAssignment::STATUS_REQUEST_CANCEL:
                $class = 'label label-warning';
                break;
            case TariffAssignment::STATUS_CANCEL:
                $class = 'label label-danger';
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
        return "{$tA->user->username}-{$tA->tariff_id}-{$tA->user_id}-{$tA->hash_id}-".md5($ip).";{$ip};{$tA->mb_limit}M;{$tA->quantity_incoming_traffic}/{$tA->quantity_outgoing_traffic};{$tA->getTimeLeftFormatDateTime()};\r\n";
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
            throw new \RuntimeException(\Yii::t('frontend', 'Failed to update configuration file.'));

        $reading = fopen($file_path, 'r');
        $writing = fopen($help_file_path, 'w');

        while (!feof($reading)) {
            $line = fgets($reading);

            if (strpos($line, "{$tariffAssignment->tariff_id}-{$tariffAssignment->user_id}-{$tariffAssignment->hash_id}") !== false) {

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
            throw new \RuntimeException(\Yii::t('frontend', 'Failed to update configuration file.'));

        $reading = fopen($file_path, 'r');
        $writing = fopen($help_file_path, 'w');

        while (!feof($reading)) {
            $line = fgets($reading);

            if (strpos($line, "{$tariffAssignment->tariff_id}-{$tariffAssignment->user_id}-{$tariffAssignment->hash_id}") === false)
                fputs($writing, $line);
        }
        fclose($reading); fclose($writing);

        unlink($file_path);
        rename($help_file_path, $file_path);
    }

    /**
     * @param $tariff_assignments TariffAssignment[]
     * @param bool $link
     * @return array
     */
    public static function checkDateTariff($tariff_assignments, $link = true)
    {
        $result = [];
        foreach ($tariff_assignments as $tariff_assignment) {

            if (!$tariff_assignment->isActive() || !$tariff_assignment->checkTimeLeft())
                continue;

            if (($tariff_assignment->getTimeLeftFormatDateTime()) < date("yy-m-d H:i"))
                continue;

            $day = $tariff_assignment->user->tariff_reminder ? $tariff_assignment->user->tariff_reminder : 3;

            if (date("yy-m-d H:i",strtotime("+$day day",time())) > ("{$tariff_assignment->getTimeLeftFormatDateTime()}")) {
                $str = "<p><strong>{$tariff_assignment->tariff->name}</strong> Окончание работы тарифа через {$tariff_assignment->getFrontTimeLeft()}";
                if ($link)
                    $str .= "<br>". Html::a(\Yii::t('frontend', 'Details'), ['view',  'id' => $tariff_assignment->tariff_id, 'hash' => $tariff_assignment->hash])."</p>";
                $result []= $str;
            }
        }
        return $result;
    }

    public static function getFrontPrice(TariffAssignment $tariff)
    {
        $prise = Yii::$app->formatter->asCurrency($tariff->getPrice(), CurrencyHelper::getActiveCode());
        $tooltip = false;

        if ($tariff->coupon) {

            if ($tariff->coupon->type == Coupons::TYPE_ONLY_PAI) {
                $tooltip = 'class="tooltipped" data-tooltip="'.\Yii::t('frontend', 'Discount for first payment only').'"';
            }

            $result = '<span '.$tooltip.' style="text-decoration: line-through">'.
                Yii::$app->formatter->asCurrency($tariff->getPrice(false, true), CurrencyHelper::getActiveCode())
                .'</span> ';
            $result .= $prise;

            return $result;
        } else {
            return $prise;
        }
    }
}
