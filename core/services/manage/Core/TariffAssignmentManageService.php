<?php


namespace core\services\manage\Core;


use core\entities\Core\Order;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use core\forms\manage\Core\TariffAssignmentEditIpsForm;
use core\forms\manage\Core\TariffAssignmentForm;
use core\forms\manage\Core\TariffAssignmentFormEditRenewal;
use core\helpers\TariffAssignmentHelper;
use core\repositories\Core\TariffAssignmentRepository;
use core\services\PayService;
use core\services\TransactionManager;

class TariffAssignmentManageService
{
    private $tariffs;
    private $payService;
    private $transactionManager;

    public function __construct(
        TariffAssignmentRepository $tariffs,
        PayService $payService,
        TransactionManager $transactionManager
    )
    {
        $this->tariffs = $tariffs;
        $this->payService = $payService;
        $this->transactionManager = $transactionManager;
    }

    public function editIPs(int $tariff_id, int $user_id, $hash_id, TariffAssignmentEditIpsForm $form)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->setIPs(
            $form->IPsArr
        );
        $this->tariffs->save($tariff);
    }

    public function edit(int $tariff_id, int $user_id, $hash_id, TariffAssignmentForm $form)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->edit(
            $form->file_path,
            $form->IPs,
            $form->mb_limit,
            $form->quantity_outgoing_traffic,
            $form->quantity_incoming_traffic,
            $form->date_to,
            $form->time_to,
            $form->ip_quantity,
            $form->discount
        );
        $this->tariffs->save($tariff);
    }

    public function activate($tariff_id, $user_id, $hash_id, $activate_pay = false)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->activate();

        $this->transactionManager->wrap(function () use($activate_pay, $tariff) {

            if ($activate_pay === true) {
                $tariff->orderItem->order->setPaid();
                $tariff->orderItem->order->save();
            }
            $this->tariffs->save($tariff);
        });
    }

    public function draft($tariff_id, $user_id, $hash_id, $set_can_pause = null)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->draft();
        if ($set_can_pause !== null) {
            $tariff->can_pause = $set_can_pause;
        }
        $this->tariffs->save($tariff);
    }

    public function cancel($tariff_id, $user_id, $hash_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->cancel();
        $this->tariffs->save($tariff);
    }

    public function deactivated($tariff_id, $user_id, $hash_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->deactivated();
        $this->tariffs->save($tariff);
    }

    public function renewalRequest($tariff_id, $user_id, $hash_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->renewalRequest();
        $this->tariffs->save($tariff);
    }

    public function cancelRequest($tariff_id, $user_id, $hash_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->cancelRequest();
        $this->tariffs->save($tariff);
    }

    public function checkDateTariff(User $user, TariffAssignment $tariff = null, $link = true)
    {
        if ($tariff) {
            $tariff_assignments[] = $tariff;
        } else {
            $tariff_assignments = $user->tariffAssignments;
        }
        return TariffAssignmentHelper::checkDateTariff($tariff_assignments, $link);
    }

    public function applyDefault($tariff_id, $user_id, $hash_id, bool $overwrite, bool $set_date)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->setDefault($overwrite, $set_date);
        $this->tariffs->save($tariff);
    }

    public function applyDefaultTrial($tariff_id, $user_id, $hash_id, bool $overwrite, bool $set_date)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->setDefaultTrial($overwrite, $set_date);
        $this->tariffs->save($tariff);
    }

    public function renewal(TariffAssignmentFormEditRenewal $form, $tariff_id, $user_id, $hash_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id, $hash_id);
        $tariff->renewal(
            $form->extend_minutes,
            $form->extend_hours,
            $form->extend_days,
            true
        );
        $this->tariffs->save($tariff);
    }

    public function getPayLink(TariffAssignment $tariff)
    {
        $order = $tariff->orderItem->order;
        return $this->payService->getPayLink($order);
    }

    public function getOrderPayLink(Order $order)
    {
        return $this->payService->getPayLink($order);
    }
}
