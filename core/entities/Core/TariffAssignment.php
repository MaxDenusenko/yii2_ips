<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffAssignmentQuery;
use core\entities\User\User;
use core\helpers\TariffAssignmentHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "tariff_assignments".
 *
 * @property int $tariff_id
 * @property int $user_id
 * @property string $hash_id
 * @property string $file_path
 * @property string $hash
 * @property int $status
 * @property int $discount
 * @property string $IPs
 * @property int $mb_limit
 * @property int $quantity_incoming_traffic
 * @property int $quantity_outgoing_traffic
 * @property string $date_to
 * @property string $time_to
 * @property int $ip_quantity
 *
 * @property Tariff $tariff
 * @property User $user
 */

class TariffAssignment extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_REQUEST_TRIAL = 3;
    const STATUS_DEACTIVATED = 4;
    const STATUS_REQUEST_RENEWAL = 5;

    const STATUS_REQUEST_CANCEL = 6;
    const STATUS_CANCEL = 7;

    public static function create($tariffId, bool $trial = false): self
    {
        $assignment = new static();
        $assignment->tariff_id = $tariffId;
        $assignment->hash = Yii::$app->security->generateRandomString(10);
        $assignment->hash_id = $assignment->hash;

        if ($trial) {
            $assignment->setDefaultTrial(true, false);
            $assignment->status = self::STATUS_REQUEST_TRIAL;
        } else {
            $assignment->setDefault(true, false);
        }

        return $assignment;
    }

    public function getPrice(): int
    {
        $countIp = count($this->getIPs());
        $price_for_ip = $this->tariff->price_for_additional_ip;
        $price = $this->tariff->price;

        if ($countIp > 1) {
            $price += ($price * $price_for_ip)/100;
        }

        if ($this->discount) {
            $price = $price - $this->discount;
        }

        return $price;
    }

    public function applyDefault(TariffDefaults $tariffDefaults, $overwrite = false, $set_time = true) : void
    {
        if ($overwrite) {
            $this->file_path = $tariffDefaults->file_path;
            $this->mb_limit = $tariffDefaults->mb_limit;
            $this->quantity_incoming_traffic = $tariffDefaults->quantity_incoming_traffic;
            $this->quantity_outgoing_traffic = $tariffDefaults->quantity_outgoing_traffic;
            $this->ip_quantity = $tariffDefaults->ip_quantity;
        }
        if ($set_time) {
            $this->renewal($tariffDefaults->extend_minutes, $tariffDefaults->extend_hours, $tariffDefaults->extend_days);
        }
    }

    public function setStatusActive()
    {
        if ($this->isActive()) {
            throw new \DomainException('Tariff is already active.');
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function activate(): void
    {
        $this->setStatusActive();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tariff_assignments}}';
    }

    public function isForTariff($id): bool
    {
        return $this->tariff_id == $id;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tariff_id' => 'Тариф',
            'user_id' => 'Пользователь',
            'file_path' => 'Пути к конфиг-файлам',
            'status' => 'Статус',
            'IPs' => 'IPs',
            'mb_limit' => 'Ограничение по траффику',
            'quantity_incoming_traffic' => 'Количество потоков входящего трафика',
            'quantity_outgoing_traffic' => 'Количество потоков исходящего трафика',
            'date_to' => 'Дата(до)',
            'time_to' => 'Время(до)',
            'ip_quantity' => 'Количество доступных ip',
            'discount' => 'Скидка',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Tariff is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function cancel(): void
    {
        if ($this->isCancel()) {
            throw new \DomainException('Tariff is already cancel.');
        }
        $this->status = self::STATUS_CANCEL;
    }

    public function deactivated(): void
    {
        if ($this->isDeactivated()) {
            throw new \DomainException('Tariff is already deactivated.');
        }
        $this->status = self::STATUS_DEACTIVATED;
    }

    public function renewalRequest(): void
    {
        if ($this->isRequestRenewal()) {
            throw new \DomainException('Уже запрошено продление тарифа');
        }
        $this->status = self::STATUS_REQUEST_RENEWAL;
    }

    public function cancelRequest(): void
    {
        if ($this->isRequestCancel()) {
            throw new \DomainException('Уже запрошена отмена тарифа');
        }
        $this->status = self::STATUS_REQUEST_CANCEL;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isCancel(): bool
    {
        return $this->status == self::STATUS_CANCEL;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function isDeactivated(): bool
    {
        return $this->status == self::STATUS_DEACTIVATED;
    }

    public function isRequestRenewal(): bool
    {
        return $this->status == self::STATUS_REQUEST_RENEWAL;
    }

    public function isRequestCancel(): bool
    {
        return $this->status == self::STATUS_REQUEST_CANCEL;
    }

    public static function find(): TariffAssignmentQuery
    {
        return new TariffAssignmentQuery(static::class);
    }

    public function getIPs(): array
    {
        $ar = array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->IPs), array('')));

        if ($this->ip_quantity && (count($ar) < $this->ip_quantity))
        {
            $n = $this->ip_quantity - count($ar);
            $i = 0;

            while ($i < $n) {
                array_push($ar, '000.000.000.'.$i);
                $i++;
            }
        }
//
        return $ar;
    }

    public function setIPs(array $files): void
    {
        $this->IPs = implode("\r\n", array_map(function ($str) {
            return trim($str);
        }, $files));
    }

    public function getFiles(): array
    {
        return array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->file_path), array('')));
    }

    public function setFiles(array $files): void
    {
        $this->file_path = implode("\r\n", array_map(function ($str) {
            return trim($str);
        }, $files));
    }

    /**
     * @param string $file_path
     * @param string $IPs
     * @param int $mb_limit
     * @param int $quantity_outgoing_traffic
     * @param int $quantity_incoming_traffic
     * @param string $date_to
     * @param string $time_to
     * @param $ip_quantity
     * @param $discount
     */
    public function edit($file_path, $IPs, $mb_limit, $quantity_outgoing_traffic,
                         $quantity_incoming_traffic, $date_to, $time_to, $ip_quantity, $discount)
    {
        $this->file_path = $file_path;
        $this->IPs = $IPs;
        $this->mb_limit = $mb_limit;
        $this->quantity_incoming_traffic = $quantity_incoming_traffic;
        $this->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $this->date_to = $date_to;
        $this->time_to = $time_to;
        $this->ip_quantity = $ip_quantity;
        $this->discount = $discount;
    }

    public function beforeDelete()
    {
        $this->checkFiles();
        $this->deleteFilesInfo();

        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        $this->checkFiles();

        if ($this->isActive())
            $this->updateFilesInfo();
        else
            $this->deleteFilesInfo();

        return parent::beforeSave($insert);
    }

    private function checkFiles(): void
    {
        $files = $this->getFiles();
        foreach ($files as $k => $file) {
            if (!file_exists($file)) {
                unset($files[$k]);
            }
        }
        $this->setFiles($files);
    }

    private function deleteFilesInfo()
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            TariffAssignmentHelper::deleteFileInfo($file, $this);
        }
    }

    private function updateFilesInfo()
    {
        $files = $this->getFiles();
        $arrIps = $this->getIPs();

        foreach ($files as $file) {

            TariffAssignmentHelper::updateFileInfo($file, $this, $arrIps, true);
        }
    }

    public function setDefaultTrial(bool $overwrite, $set_date = true)
    {
        $tariff = Tariff::findOne($this->tariff_id);
        $default = $tariff->defaultTrial[0];
        $this->applyDefault($default, $overwrite, $set_date);
    }

    public function setDefault(bool $overwrite, $set_date = true)
    {
        $tariff = Tariff::findOne($this->tariff_id);
        $default = $tariff->default[0];
        $this->applyDefault($default, $overwrite, $set_date);
    }

    public function renewal($extend_minutes, $extend_hours, $extend_days, $add_current_time = false)
    {
        if ($add_current_time && $this->date_to && $this->time_to) {

            $this->date_to = date("yy-m-d",strtotime("+".(int) $extend_days." day", strtotime($this->date_to) ));
            $this->time_to = date("H:i",strtotime("+".(int) $extend_hours." hours", strtotime($this->time_to) ));
            $this->time_to = date("H:i",strtotime("+".(int) $extend_minutes." minutes", strtotime($this->time_to) ));
        } else {

            $this->date_to = date("yy-m-d",strtotime("+".(int) $extend_days." day",time()));
            $this->time_to = date("H:i",strtotime("+".(int) $extend_hours." hours",time()));
            $this->time_to = date("H:i",strtotime("+".(int) $extend_minutes." minutes", strtotime($this->time_to)));
        }
    }
}
