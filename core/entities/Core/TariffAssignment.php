<?php

namespace core\entities\Core;

use core\entities\Core\queries\TariffAssignmentQuery;
use core\entities\User\User;
use core\helpers\TariffAssignmentHelper;
use DomainException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariff_assignments".
 *
 * @property int $tariff_id
 * @property int $user_id
 * @property string $hash_id
 * @property int $order_item_id
 * @property string $file_path
 * @property string $hash
 * @property int $status
 * @property int $discount
 * @property string $IPs
 * @property int $mb_limit
 * @property string $quantity_incoming_traffic
 * @property string $quantity_outgoing_traffic
 * @property string $date_to
 * @property string $time_to
 * @property int $ip_quantity
 * @property int $time_left
 * @property boolean $can_pause
 * @property int $coupon_id
 *
 * @property Coupons $coupon
 * @property Tariff $tariff
 * @property User $user
 * @property OrderItem $orderItem
 */

class TariffAssignment extends ActiveRecord
{
    private $oldRecord;

    const STATUS_DRAFT = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_REQUEST_TRIAL = 3;
    const STATUS_DEACTIVATED = 4;
    const STATUS_REQUEST_RENEWAL = 5;

    const STATUS_REQUEST_CANCEL = 6;
    const STATUS_CANCEL = 7;

    public static function create($tariffId, bool $trial = false, OrderItem $orderItem = null): self
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

        if ($orderItem) {
            $assignment->order_item_id = $orderItem->id;
        }

        return $assignment;
    }

    /**
     * @param null|string $coupon_code
     * @return bool
     */
    public function setCoupon($coupon_code = null)
    {
        if (!$coupon_code)
            return false;

        $coupon = Coupons::find()->where(['code' => $coupon_code])->one();
        if ($coupon && $coupon->per_cent) {
            $this->coupon_id = $coupon->id;
            return  true;
        }

        return false;
    }

    public function addCouponUse(float $sum)
    {
        $sum = $sum ? $sum : $this->getPrice();

        $coupon = $this->coupon;
        if (!$coupon) return false;

        $couponUse = CouponUses::create($coupon->id, $this->user_id, $this->hash_id, $sum);

        if ($couponUse->save())
            return true;

        return false;
    }

    public function activatePause()
    {
        $this->can_pause = true;
    }

    public function addAdditionalIp(int $count)
    {
        $this->ip_quantity += $count;
    }

    public function getPriceWithoutPerSent(int $per_cent, $price = false)
    {
        $price = $price ? $price : $this->tariff->getPrice();
        $price -= ($price * $per_cent)/100;

        return $price;
    }

    public function getDiscountPrice($price = false, $request_renewal = false)
    {
        $price = $price ? $price : $this->tariff->getPrice();
        $coupon = $this->coupon;

        if ($coupon) {

            switch ($coupon->type) {
                case Coupons::TYPE_PAI_AND_RENEWAL:
                    $price = $this->getPriceWithoutPerSent($coupon->per_cent, $price);
                    break;

                case Coupons::TYPE_ONLY_PAI:
                    if ($request_renewal !== true) {
                        $price = $this->getPriceWithoutPerSent($coupon->per_cent, $price);
                    }
                    break;
            }
        }

        return $price;
    }

    public function getPriceWithAdditionalIP($price = false)
    {
        $price = $price ? $price : $this->tariff->getPrice();

        $curIp          = $this->tariff->default[0]->ip_quantity;
        $countIp        = count($this->getIPs());
        $price_for_ip   = $this->tariff->price_for_additional_ip;

        if ($countIp > 1 && $curIp > 1 && $countIp > $curIp && ($countIp - $curIp) > 0 && $price_for_ip) {
            $price += (($price * $price_for_ip)/100) * ($countIp - $curIp);
        }

        return $price;
    }

    public function getPrice($request_renewal = false, $withoutDiscount = false, $withoutAdditionalIP = false): float
    {
        if ($withoutAdditionalIP) {
            $price = $this->tariff->getPrice();
        } else {
            $price = $this->getPriceWithAdditionalIP();
        }
        if ($withoutDiscount === false)
            $price = $this->getDiscountPrice($price, $request_renewal);

        return round($price, 2);
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
            throw new DomainException(Yii::t('frontend', 'Tariff is already active.'));
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function activate(): void
    {
        if (!$this->checkIssetTimeLeft()) {
            $this->setDefault(false, true);
        }
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
            'tariff_id' => Yii::t('frontend', 'Tariff'),
            'user_id' => Yii::t('frontend', 'User'),
            'file_path' => Yii::t('frontend', 'Paths to config files'),
            'status' => Yii::t('frontend', 'Status'),
            'IPs' => 'IP',
            'mb_limit' => Yii::t('frontend', 'Traffic limit'),
            'quantity_incoming_traffic' => Yii::t('frontend', 'Number of incoming traffic streams'),
            'quantity_outgoing_traffic' => Yii::t('frontend', 'Number of outgoing traffic flows'),
            'date_to' => Yii::t('frontend', 'Date (before)'),
            'time_to' => Yii::t('frontend', 'Time (before)'),
            'ip_quantity' => Yii::t('frontend', 'Number of ip available'),
            'discount' => Yii::t('frontend', 'A discount %'),
            'time_left' => Yii::t('frontend', 'Time until the end of the tariff'),
            'can_pause' => Yii::t('frontend', 'Ability to pause'),
            'coupon_id' => Yii::t('frontend', 'Coupon'),
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

    /**
     * @return ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(Coupons::className(), ['id' => 'coupon_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['id' => 'order_item_id']);
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException(Yii::t('frontend', 'Tariff is already draft.'));
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function cancel(): void
    {
        if ($this->isCancel()) {
            throw new DomainException(Yii::t('frontend', 'Tariff is already cancel.'));
        }
        $this->status = self::STATUS_CANCEL;
    }

    public function deactivated(): void
    {
        if ($this->isDeactivated()) {
            throw new DomainException(Yii::t('frontend', 'Tariff is already deactivated.'));
        }
        $this->status = self::STATUS_DEACTIVATED;
    }

    public function renewalRequest(): void
    {
        if ($this->isRequestRenewal()) {
            throw new DomainException(Yii::t('frontend', 'Already requested a tariff extension'));
        }
        $this->status = self::STATUS_REQUEST_RENEWAL;
    }

    public function cancelRequest(): void
    {
        if ($this->isRequestCancel()) {
            throw new DomainException(Yii::t('frontend', 'Tariff cancellation already requested'));
        }
        $this->status = self::STATUS_REQUEST_CANCEL;
    }

    public function canDraft()
    {
        return (boolean)$this->can_pause;
    }

    public function isPaid(): bool
    {
        return $this->orderItem->order->isPaid();
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
            $i = 1+count($ar);

            while ($i < $this->ip_quantity+1) {
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
//        $this->date_to = $date_to;
//        $this->time_to = $time_to;
        $this->ip_quantity = $ip_quantity;
        $this->discount = $discount;
    }

    public function beforeDelete()
    {
        $this->checkFiles();
        $this->deleteFilesInfo();

        return parent::beforeDelete();
    }

    public function afterFind()
    {
        $this->oldRecord = clone $this;
        return parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->checkFiles();

        if ($this->isActive())
            $this->updateFilesInfo();
        else
            $this->deleteFilesInfo();

        if ($this->oldRecord) {

            if (
                ($this->oldRecord->ip_quantity != $this->ip_quantity)
                &&
                $this->tariff->price_for_additional_ip ||
                $this->discount != $this->oldRecord->discount
            ) {
                $renewalItems = RenewalOrderItem::find()->where(['product_hash' => $this->hash_id])->joinWith(['order'])->all();

                if (count($renewalItems)) {
                    foreach ($renewalItems as $renewalItem) {
                        if (!$renewalItem->order->isPaid()) {
                            $renewalItem->order->canceled();
                            $renewalItem->order->save();
                        }
                    }
                }

                $additionalItems = AdditionalOrderItem::find()->where(['product_hash' => $this->hash_id])->joinWith(['order'])->all();

                if (count($additionalItems)) {
                    foreach ($additionalItems as $additionalItem) {
                        if (!$additionalItem->order->isPaid()) {
                            $additionalItem->order->canceled();
                            $additionalItem->order->save();
                        }
                    }
                }
            }
        }

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
        $tariff_time_min = (int)$extend_days * 1440
            + (int)$extend_hours * 60
            +  (int)$extend_minutes;

        $this->setTimeLeft($tariff_time_min, $add_current_time);
    }

    public function checkIssetTimeLeft()
    {
        return strlen($this->time_left) > 0;
    }

    public function checkTimeLeft()
    {
        return (int)$this->time_left ? true : false;
    }

    public function getTimeLeftFormatDateTime()
    {
        $timestamp = strtotime("+{$this->getTimeLeft()} minutes",time());
        return date('Y-m-d H:i:s', $timestamp);
    }

    public function getFrontTimeLeft()
    {
        return $this->downCounter($this->getTimeLeftFormatDateTime());
    }

    public function setTimeLeft($time_min, $add_current_time = false)
    {
        if ($add_current_time) {
            $this->time_left += $time_min;
        } else {
            $this->time_left = $time_min;
        }
    }

    public function getTimeLeft()
    {
        return $this->time_left;
    }

    public function getDefaultMin()
    {
        return (int)$this->tariff->default[0]->extend_days * 1440
            + (int)$this->tariff->default[0]->extend_hours * 60
            +  (int)$this->tariff->default[0]->extend_minutes;
    }

    public function downCounter($date)
    {
        $check_time = strtotime($date) - time();
        if($check_time <= 0){
            return false;
        }

        $days = floor($check_time/86400);
        $hours = floor(($check_time%86400)/3600);
        $minutes = floor(($check_time%3600)/60);
        $seconds = $check_time%60;

        $str = '';
        if($days > 0) $str .= $this->declension($days,array('день','дня','дней')).' ';
        if($hours > 0) $str .= $this->declension($hours,array('час','часа','часов')).' ';
        if($minutes > 0) $str .= $this->declension($minutes,array('минута','минуты','минут')).' ';
        if($seconds > 0) $str .= $this->declension($seconds,array('секунда','секунды','секунд'));

        return $str;
    }

    public static function declension($digit ,$expr, $onlyWord = false)
    {

        if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i=preg_replace('/[^0-9]+/s','',$digit)%100;
        if($onlyWord) $digit='';

        if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
        else
        {
            $i%=10;
            if($i==1) $res=$digit.' '.$expr[0];
            elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
            else $res=$digit.' '.$expr[2];
        }

        return trim($res);
    }
}
