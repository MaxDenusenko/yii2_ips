<?php


namespace core\services\manage\Core;


use core\entities\Core\Tariff;
use core\forms\manage\Core\TariffForm;
use core\repositories\Core\TariffRepository;
use yii\db\StaleObjectException;

class TariffManageService
{
    private $tariffs;

    public function __construct(
        TariffRepository $tariffs
    )
    {
        $this->tariffs = $tariffs;
    }

    public function activate($id): void
    {
        $tariff = $this->tariffs->get($id);
        $tariff->activate();
        $this->tariffs->save($tariff);
    }

    public function draft($id): void
    {
        $tariff = $this->tariffs->get($id);
        $tariff->draft();
        $this->tariffs->save($tariff);
    }

    /**
     * @param TariffForm $form
     * @return Tariff
     */
    public function create(TariffForm $form): Tariff
    {
        $tariff = Tariff::create(
            $form->name,
            $form->number,
            $form->quantity,
            $form->price,
            Tariff::STATUS_DRAFT
        );
        $this->tariffs->save($tariff);
        return $tariff;
    }

    /**
     * @param $id
     * @param TariffForm $form
     */
    public function edit($id, TariffForm $form): void
    {
        $tariff = $this->tariffs->get($id);
        $tariff->edit(
            $form->name,
            $form->number,
            $form->quantity,
            $form->price
        );
        $this->tariffs->save($tariff);
    }

    /**
     * @param $id
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function remove($id): void
    {
        $tariff = $this->tariffs->get($id);
        $this->tariffs->remove($tariff);
    }
}
