<?php


namespace frontend\controllers;


use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;
use CoinbaseCommerce\Resources\Checkout;
use CoinbaseCommerce\Resources\Event;
use yii\web\Controller;

class CoinController extends Controller
{

    public function actionIndex()
    {
        ApiClient::init('57c293a1-4ff4-462a-b356-74c99c9ea13e');

//        $checkoutObj = new Checkout([
//            "description" => "Mastering the Transition to the Information Age",
//            "local_price" => [
//                "amount" => "1.00",
//                "currency" => "USD"
//            ],
//            "name" => "test item 15 edited",
//            "pricing_type" => "fixed_price",
//            "requested_info" => ["email"]
//        ]);
//
//        try {
//            $checkoutObj->save();
//            echo sprintf("Successfully created new checkout with id: %s \n", $checkoutObj->id);
//
//            echo '<pre>';
//            print_r($checkoutObj);
//            echo '</pre>';
//
//        } catch (\Exception $exception) {
//            echo sprintf("Enable to create checkout. Error: %s \n", $exception->getMessage());
//        }



        $params = [
            'order' => 'desc'
        ];

        $allCheckouts = Checkout::getAll($params);

        $allCharges = Charge::getAll();
//        $allEvents = Event::getAll();

        echo '<pre>';
//        print_r($allEvents);
        print_r($allCharges);
        print_r($allCheckouts);
        echo '</pre>';

        die();
    }
}
