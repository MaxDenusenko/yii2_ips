<?php


namespace core\services\manage\Core;


use core\entities\Core\Tariff;
use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffForm;
use core\repositories\Core\TariffDefaultsRepository;
use core\repositories\Core\TariffRepository;
use core\services\TransactionManager;
use Throwable;
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
     * @param Tariff $form
     * @return Tariff
     */
    public function create(Tariff $form): Tariff
    {
        $form->addDefault($form->defaultComposite);
        $form->addDefaultTrial($form->defaultTrialComposite);

        $this->tariffs->save($form);
        return $form;
    }

    /**
     * @param $id
     * @param Tariff $form
     */
    public function edit($id, Tariff $form): void
    {
        $this->transaction->wrap(function () use ($form) {

            $default_old = TariffDefaults::findOne($form->default[0]->id);
            $default_old->attributes = $form->defaultComposite->attributes;
            $this->tariffDefaults->save($default_old);

            $defaultTrial_old = TariffDefaults::findOne($form->defaultTrial[0]->id);
            $defaultTrial_old->attributes = $form->defaultTrialComposite->attributes;
            $this->tariffDefaults->save($defaultTrial_old);

            $this->tariffs->save($form);

        });
    }

    /**
     * @param $id
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove($id): void
    {
        $tariff = $this->tariffs->get($id);
        $this->tariffs->remove($tariff);
    }
}
