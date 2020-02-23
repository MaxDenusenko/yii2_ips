<?php


namespace frontend\controllers\cabinet;


use core\entities\Core\AdditionalOrderItem;
use core\entities\Core\Order;
use core\entities\Core\RenewalOrderItem;
use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use core\forms\manage\Core\AdditionalIpOrderForm;
use core\forms\manage\Core\AdditionalIpOrderItemForm;
use core\forms\manage\Core\OrderForm;
use core\forms\manage\Core\RenewalForm;
use core\forms\manage\Core\TariffAssignmentEditIpsForm;
use core\repositories\NotFoundException;
use core\services\manage\Core\TariffAssignmentManageService;
use DomainException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class MyTariffsController extends Controller
{
    private $user;
    private $service;

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
                    'cancel' => ['POST'],
                    'renewal' => ['POST'],
                    'pay' => ['POST'],
                    'pay-link' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                ],
            ],
        ];
    }

    public function actionActivate($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        if (!$tariff->isPaid()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'Tariff not paid'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        if (!$tariff->isDraft()) {
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        try {
            $this->service->activate($tariff->tariff_id, $tariff->user_id, $tariff->hash_id);
            Yii::$app->session->setFlash('success', \Yii::t('frontend', \Yii::t('frontend', 'Tariff activated')));
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
        }
        return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
    }

    public function actionDraft($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        if (!$tariff->isPaid()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'Tariff not paid'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        if ($tariff->isDraft()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'The tariff is already stopped'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        if (!$tariff->canDraft()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'This tariff cannot be paused.'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        try {
            $this->service->draft($tariff->tariff_id, $tariff->user_id, $tariff->hash_id, false);
            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'The tariff stopped'));
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
        }
        return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
    }

    public function __construct($id, $module, TariffAssignmentManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->user = User::findOne(\Yii::$app->user->id);
        $this->service = $service;
        $this->layout = 'cabinet';
    }

    public function actionPay($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        if ($tariff->isPaid()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'Tariff already paid'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        $pay_link = $this->service->getPayLink($tariff);
        return $this->redirect($pay_link);
    }

    public function actionPayLink($id, $hash, $idOrder)
    {
        try {
            $this->findTariffAssignment($id, $hash);
            $order = Order::findOne($idOrder);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        if ($order->isPaid()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'This order has already been paid'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        if ($order->isCanceled()) {
            Yii::$app->session->setFlash('info', \Yii::t('frontend', 'Payment is not relevant'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        $pay_link = $this->service->getOrderPayLink($order);
        return $this->redirect($pay_link);
    }

    public function actionIndex()
    {
        $tariffs = $this->user->getTariffAssignments()->andWhere(['!=', 'status',  TariffAssignment::STATUS_CANCEL])->all();
        $infoAr = $this->service->checkDateTariff($this->user);

        return $this->render('index', [
            'tariffs' => $tariffs,
            'infoAr' => $infoAr
        ]);
    }

    public function actionView($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        $infoAr = $this->service->checkDateTariff($this->user, $tariff, false);
        $orderForm = new RenewalForm();
        $orderFormAddIP = new AdditionalIpOrderForm();
        $renewal_items = RenewalOrderItem::find()->where(['product_user' => $this->user->id, 'product_hash' => $tariff->hash_id])->all();
        $additional_items = AdditionalOrderItem::find()->where(['product_user' => $this->user->id, 'product_hash' => $tariff->hash_id])->all();

        return $this->render('tariff_item', [
            'tariff' => $tariff,
            'orderForm' => $orderForm,
            'infoAr' => $infoAr,
            'renewal_items' => $renewal_items,
            'orderFormAddIP' => $orderFormAddIP,
            'additional_items' => $additional_items,
        ]);
    }

    public function actionEdit($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        if (! (int)$tariff->ip_quantity) {
            Yii::$app->session->setFlash('warning', \Yii::t('frontend', 'Change ip is not available!'));
            return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
        }

        $form = new TariffAssignmentEditIpsForm($tariff);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editIPs($id, $this->user->id, $hash, $form);
                Yii::$app->session->setFlash('success', \Yii::t('frontend', 'Information has been updated!'));
                return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            }
        }

        return $this->render('edit', [
            'tariff' => $tariff,
            'model' => $form,
        ]);
    }

    public function actionRenewal($id, $hash)
    {
        try {
            $tariff = $this->findTariffAssignment($id, $hash);
            if (!$tariff->isDeactivated()) {
                throw new NotFoundException(\Yii::t('frontend', 'Tariff not found'));
            }
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        try {
            $this->service->renewalRequest($id, $this->user->id, $hash);
            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'Request for tariff extension sent!'));
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
    }

    /**
     * @param $id
     * @param $hash
     * @return Response
     */
    public function actionCancel($id, $hash)
    {
        try {
            $this->service->cancelRequest($id, $this->user->id, $hash);
            Yii::$app->session->setFlash('success', \Yii::t('frontend', 'Request for tariff cancellation sent!'));
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        return $this->redirect(['view', 'id' => $id, 'hash' => $hash]);
    }


    /**
     * @param $id
     * @param $hash
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findTariffAssignment($id, $hash)
    {
        $tariff = TariffAssignment::find()->where(['tariff_id' => $id, 'user_id' => $this->user->id, 'hash_id' => $hash])->notCancel()->one();
        if (!$tariff || !$this->user->issetTariff($id, $this->user->id, $hash))
            throw new NotFoundException(\Yii::t('frontend', 'Tariff not found'));

        return $tariff;
    }
}
