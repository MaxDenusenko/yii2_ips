<?php


namespace core\services\manage\Core;


use core\entities\Core\Tariff;
use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffForm;
use core\repositories\Core\TariffDefaultsRepository;
use core\repositories\Core\TariffRepository;
use core\services\TransactionManager;
use yii\db\StaleObjectException;

class TariffManageService
{
    private $tariffs;
    private $tariffDefaults;
    private $transaction;

    public function __construct(
        TariffRepository $tariffs,
        TariffDefaultsRepository $tariffDefaults,
        TransactionManager $transaction
    )
    {
        $this->tariffs = $tariffs;
        $this->tariffDefaults = $tariffDefaults;
        $this->transaction = $transaction;
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
            $form->price,
            Tariff::STATUS_DRAFT,
            $form->proxy_link,
            $form->description,
            $form->price_for_additional_ip,
            $form->qty_proxy,
            $form->category_id
        );

        $tariff->addDefault($form->default);
        $tariff->addDefaultTrial($form->defaultTrial);

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
            $form->price,
            $form->proxy_link,
            $form->description,
            $form->price_for_additional_ip,
            $form->qty_proxy,
            $form->category_id
        );

        $this->transaction->wrap(function () use ($tariff, $form) {

            $default_old = TariffDefaults::findOne($tariff->default[0]->id);
            $default_old->attributes = $form->default->attributes;
            $this->tariffDefaults->save($default_old);

            $defaultTrial_old = TariffDefaults::findOne($tariff->defaultTrial[0]->id);
            $defaultTrial_old->attributes = $form->defaultTrial->attributes;
            $this->tariffDefaults->save($defaultTrial_old);

            $this->tariffs->save($tariff);

        });
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
