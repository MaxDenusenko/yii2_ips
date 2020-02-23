<?php


namespace frontend\controllers;


use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;
use core\entities\CoinPay;
use yii\web\Controller;
use CoinbaseCommerce\Webhook;
use Yii;

class CoinController extends Controller
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== "webhook");

        return parent::beforeAction($action);
    }

    public function actionWebhook()
    {
        $secret = Yii::$app->params['coin_commerce']['secret_key'];
        $headerName = 'X-Cc-Webhook-Signature';
        $headers = getallheaders();
        $signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        $payload = trim(file_get_contents('php://input'));

        try {
            $event = Webhook::buildEvent($payload, $signraturHeader, $secret);
            http_response_code(200);

            $charge_code = $event->data->code;
            ApiClient::init(Yii::$app->params['coin_commerce']['api_key']);
            $allCharges = Charge::getAll();

            foreach ($allCharges as $charge) {
                if ($charge->code == $charge_code) {
                    $checkout_id = $charge->checkout['id'];
                    if ($checkout_id) {
                        $coin_pay = CoinPay::find()->where(['identity' => $checkout_id])->one();
                        if ($coin_pay) {
                            $coin_pay->status = $event->type;
                            $coin_pay->charge_id = $charge->id;
                            $coin_pay->save();
                        }
                    }
                }
                break;
            }

            echo sprintf('Successully verified event with id %s and type %s.', $event->data->code, $event->type);

        } catch (\Exception $exception) {
            http_response_code(400);
            echo 'Error occured. ' . $exception->getMessage();
        }
    }
}
