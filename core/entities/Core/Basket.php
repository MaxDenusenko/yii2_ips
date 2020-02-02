<?php


namespace core\entities\Core;


use core\forms\manage\Core\AddToBasketForm;
use core\helpers\CurrencyHelper;
use Yii;
use yii\base\Model;

class Basket extends Model
{
    /**
     * @param AddToBasketForm $form
     * @throws \yii\db\Exception
     */
    public function addToBasket(AddToBasketForm $form) {

        $id = $form->id_product;
        $count = $form->count;
        $currency = CurrencyHelper::getActiveCode();

        $count = (int)$count;
        if ($count < 1) {
            return;
        }
        $id = abs((int)$id);

        $product = Tariff::findOne($id);
        if (empty($product)) {
            return;
        }

        $session = Yii::$app->session;
        $session->open();

        if (!$session->has('basket')) {
            $session->set('basket', []);
            $basket = [];
        } else {
            $basket = $session->get('basket');
        }

        $basket['currency'] = $basket['currency'] ? $basket['currency'] : $currency;

        if (isset($basket['products'][$product->id])) { // такой товар уже есть?

            $count = $basket['products'][$product->id]['count'] + $count;
            $basket['products'][$product->id]['count'] = $count;

        } else { // такого товара еще нет

            $basket['products'][$product->id]['name'] = $product->getLabel();
            $basket['products'][$product->id]['price'] = $product->getPrice($basket['currency']);
            $basket['products'][$product->id]['count'] = $count;
        }

        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;

        $session->set('basket', $basket);
    }

    /**
     * Метод удаляет товар из корзины
     * @param $id
     */
    public function removeFromBasket($id) {

        $id = abs((int)$id);

        $session = Yii::$app->session;
        $session->open();

        if (!$session->has('basket')) {
            return;
        }

        $basket = $session->get('basket');
        if (!isset($basket['products'][$id])) {
            return;
        }

        unset($basket['products'][$id]);

        if (count($basket['products']) == 0) {
            $session->set('basket', []);
            return;
        }

        $amount = 0.0;
        foreach ($basket['products'] as $item) {
            $amount = $amount + $item['price'] * $item['count'];
        }
        $basket['amount'] = $amount;

        $session->set('basket', $basket);
    }

    /**
     * Метод возвращает содержимое корзины
     */
    public function getBasket() {
        $session = Yii::$app->session;
        $session->open();
        if (!$session->has('basket')) {
            $session->set('basket', []);
            return [];
        } else {
            return $session->get('basket');
        }
    }

    /**
     * Метод удаляет все товары из корзины
     */
    public function clearBasket() {
        $session = Yii::$app->session;
        $session->open();
        $session->set('basket', []);
    }

    public function updateBasket($data) {
//        $count ? $count : 1
        foreach ($data['count'] as $id => $count) {
            $form = new AddToBasketForm(['count' => $count, 'id_product' => $id]);
            if($form->validate()) {
                $this->removeFromBasket($id);
                $this->addToBasket($form);
            }
        }
    }
}
