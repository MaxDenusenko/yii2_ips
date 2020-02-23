<?php


namespace core\forms\manage\User;


use core\entities\Core\PaymentMethod;
use core\entities\Core\Tariff;
use core\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TariffsFom extends Model
{
    public $list = [];
    public $payment_method_id;

    public function __construct(User $user = null, $config = [])
    {
//        if ($user) {
//            $this->list = ArrayHelper::getColumn($user->tariffAssignments, 'tariff_id');
//        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['list', 'payment_method_id'], 'integer'],
        ];
    }

    public function tariffsList(): array
    {
        return ArrayHelper::map(Tariff::find()->active()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function paymentMethodList(): array
    {
        return ArrayHelper::map(PaymentMethod::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }
}
