<?php


namespace core\services\manage\Core;


use core\entities\User\User;
use core\forms\manage\Core\TariffAssignmentEditIpsForm;
use core\forms\manage\Core\TariffAssignmentForm;
use core\helpers\TariffAssignmentHelper;
use core\repositories\Core\TariffAssignmentRepository;

class TariffAssignmentManageService
{
    private $tariffs;

    public function __construct(TariffAssignmentRepository $tariffs)
    {
        $this->tariffs = $tariffs;
    }

    public function editIPs(int $tariff_id, int $user_id, TariffAssignmentEditIpsForm $form)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id);
        $tariff->setIPs(
            $form->IPsArr
        );
        $this->tariffs->save($tariff);
    }

    public function edit(int $tariff_id, int $user_id, TariffAssignmentForm $form)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id);
        $tariff->edit(
            $form->file_path,
            $form->IPs,
            $form->mb_limit,
            $form->quantity_outgoing_traffic,
            $form->quantity_incoming_traffic,
            $form->date_to,
            $form->time_to,
            $form->ip_quantity
        );
        $this->tariffs->save($tariff);
    }

    public function activate($tariff_id, $user_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id);
        $tariff->activate();
        $this->tariffs->save($tariff);
    }

    public function activateTrial($tariff_id, $user_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id);
        $tariff->activate();
        $tariff->setTrial();
        $this->tariffs->save($tariff);
    }

    public function draft($tariff_id, $user_id)
    {
        $tariff = $this->tariffs->get($tariff_id, $user_id);
        $tariff->draft();
        $this->tariffs->save($tariff);
    }

    public function checkDateTariff(User $user)
    {
        $tariff_assignments = $user->tariffAssignments;
        return TariffAssignmentHelper::checkDateTariff($tariff_assignments);
    }

}
