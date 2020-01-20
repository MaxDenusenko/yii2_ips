<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffAssignmentQuery;
use core\entities\User\User;
use core\helpers\TariffAssignmentHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariff_assignments".
 *
 * @property int $tariff_id
 * @property int $user_id
 * @property string $file_path
 * @property int $status
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

    public static function create($tariffId, bool $trial = false): self
    {
        $assignment = new static();
        $assignment->tariff_id = $tariffId;
        if ($trial)
            $assignment->status = self::STATUS_REQUEST_TRIAL;

        return $assignment;
    }

    public function setTrial()
    {
        $this->date_to = date("yy-m-d",strtotime('+30 minutes',time()));
        $this->time_to = date("H:i",strtotime('+30 minutes',time()));
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

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Tariff is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Tariff is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public static function find(): TariffAssignmentQuery
    {
        return new TariffAssignmentQuery(static::class);
    }

    public function getIPs(): array
    {
        return array_map(function ($str) {
            return trim($str);
        }, array_diff(explode("\n", $this->IPs), array('')));
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
     */
    public function edit($file_path, $IPs, $mb_limit, $quantity_outgoing_traffic, $quantity_incoming_traffic, $date_to, $time_to, $ip_quantity)
    {
        $this->file_path = $file_path;
        $this->IPs = $IPs;
        $this->mb_limit = $mb_limit;
        $this->quantity_incoming_traffic = $quantity_incoming_traffic;
        $this->quantity_outgoing_traffic = $quantity_outgoing_traffic;
        $this->date_to = $date_to;
        $this->time_to = $time_to;
        $this->ip_quantity = $ip_quantity;
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
}
