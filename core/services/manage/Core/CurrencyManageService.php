<?php


namespace core\services\manage\Core;


use core\entities\Core\Currency;
use core\forms\manage\Core\CurrencyForm;
use core\repositories\Core\CurrencyRepository;
use core\services\TransactionManager;

class CurrencyManageService
{
    private $currencies;
    private $transaction;

    public function __construct(
        CurrencyRepository $currencies,
        TransactionManager $transaction
    )
    {
        $this->currencies = $currencies;
        $this->transaction = $transaction;
    }

    /**
     * @param CurrencyForm $form
     * @return Currency
     */
    public function create(CurrencyForm $form): Currency
    {
        $currency = Currency::create(
            $form->code,
            $form->symbol
        );

        $this->currencies->save($currency);
        return $currency;
    }

    /**
     * @param $id
     * @param CurrencyForm $form
     */
    public function edit($id, CurrencyForm $form): void
    {
        $currency = $this->currencies->get($id);
        $currency->edit(
            $form->code,
            $form->symbol
        );
        $this->currencies->save($currency);
    }

    /**
     * @param $id
     * @throws \Throwable
     */
    public function remove($id): void
    {
        $currency = $this->currencies->get($id);
        $this->currencies->remove($currency);
    }

    public function activate($id)
    {
        $currency = $this->currencies->get($id);
        $currency->activate();

        $this->transaction->wrap(function () use($currency) {
            $currencies = Currency::find()->where(['not in', 'id', $currency->id])->all();
            if (!empty($currencies)) {
                foreach ($currencies as $currency_item) {
                    $this->deactivate($currency_item->id);
                }
            }
            $this->currencies->save($currency);
        });
    }

    public function deactivate($id)
    {
        $currency = $this->currencies->get($id);
        $currency->deactivate();
        $this->currencies->save($currency);
    }

    public function inBase($id)
    {
        $currency = $this->currencies->get($id);
        $currency->inBase();
        $this->currencies->save($currency);
    }

    public function setBase($id)
    {
        $currency = $this->currencies->get($id);
        $currency->setBase();

        $this->transaction->wrap(function () use($currency) {
            $currencies = Currency::find()->where(['not in', 'id', $currency->id])->all();
            if (!empty($currencies)) {
                foreach ($currencies as $currency_item) {
                    $this->inBase($currency_item->id);
                }
            }
            $this->currencies->save($currency);
        });
    }
}
