<?php


namespace frontend\controllers;


use core\entities\Core\TariffAssignment;
use core\forms\manage\Core\AdditionalIpOrderForm;
use core\forms\manage\Core\OrderForm;
use core\forms\manage\Core\RenewalForm;
use core\services\manage\Core\OrderManageService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class OrderController extends Controller
{
    private $service;

    public function __construct($id, $module, OrderManageService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                    'renewal' => ['POST'],
                    'additional-ip' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $form = new OrderForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $order = $this->service->create($form, Yii::$app->user->id);
                $tariffAssignment = $order->orderItems[0]->tariffAssignment;
                return $this->redirect(['cabinet/my-tariffs/view', 'id' => $tariffAssignment->tariff_id, 'hash' => $tariffAssignment->hash]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->goBack();
    }

    public function actionAdditionalIp()
    {
        $form = new AdditionalIpOrderForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()
            && $form->assignment->product_user == Yii::$app->user->id)
        {
            /** @var TariffAssignment $tariffAssignment */
            $tariffAssignment = TariffAssignment::find()->active()->where(['hash_id' => $form->assignment->product_hash])->one();

            if (!$tariffAssignment) {
                Yii::$app->session->setFlash('error', \Yii::t('frontend', 'Tariff not active'));
                return $this->redirect(['cabinet/my-tariffs/view', 'id' => $form->assignment->product_id, 'hash' => $form->assignment->product_hash]);
            }

            if (!$tariffAssignment->tariff->price_for_additional_ip) {
                Yii::$app->session->setFlash('error', \Yii::t('frontend', \Yii::t('frontend', 'Failed to place an order')));
                return $this->redirect(['cabinet/my-tariffs/view', 'id' => $form->assignment->product_id, 'hash' => $form->assignment->product_hash]);
            }

            try {
                $this->service->createAdditionalIpRequest($form, Yii::$app->user->id);
                return $this->redirect(['cabinet/my-tariffs/view', 'id' => $tariffAssignment->tariff_id, 'hash' => $tariffAssignment->hash]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->goBack();
    }

    public function actionRenewal()
    {
        $form = new RenewalForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate() && $form->assignment->product_user == Yii::$app->user->id) {
            try {
                $order = $this->service->createRenewal($form, Yii::$app->user->id);
                $tariffAssignment = $order->renewalOrderItems[0]->tariffAssignment;
                return $this->redirect(['cabinet/my-tariffs/view', 'id' => $tariffAssignment->tariff_id, 'hash' => $tariffAssignment->hash]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->goBack();
    }
}
