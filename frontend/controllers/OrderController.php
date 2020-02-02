<?php


namespace frontend\controllers;


use core\forms\manage\Core\OrderForm;
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
}
