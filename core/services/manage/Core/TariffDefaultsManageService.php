<?php


namespace core\services\manage\Core;


use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffDefaultsForm;
use core\repositories\Core\TariffDefaultsRepository;

class TariffDefaultsManageService
{
    private $tariffs;

    public function __construct(
        TariffDefaultsRepository $tariffs
    )
    {
        $this->tariffs = $tariffs;
    }

    /**
     * @param TariffDefaultsForm $form
     * @return TariffDefaults
     */
    public function create(TariffDefaultsForm $form): TariffDefaults
    {
        $tariff = TariffDefaults::create(
            $form->mb_limit,
            $form->quantity_incoming_traffic,
            $form->quantity_outgoing_traffic,
            $form->name
        );
        $this->tariffs->save($tariff);
        return $tariff;
    }

    /**
     * @param $id
     * @param TariffDefaultsForm $form
     */
    public function edit($id, TariffDefaultsForm $form): void
    {
        $tariff = $this->tariffs->get($id);
        $tariff->edit(
            $form->mb_limit,
            $form->quantity_incoming_traffic,
            $form->quantity_outgoing_traffic,
            $form->name
        );
        $this->tariffs->save($tariff);
    }

    /**
     * @param $id
     * @throws \Throwable
     */
    public function remove($id): void
    {
        $tariff = $this->tariffs->get($id);
        $this->tariffs->remove($tariff);
    }
}
