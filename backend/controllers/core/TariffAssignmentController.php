<?php

namespace backend\controllers\core;

use core\entities\Core\AdditionalOrderItem;
use core\entities\Core\RenewalOrderItem;
use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffAssignmentForm;
use core\forms\manage\Core\TariffAssignmentFormEditRenewal;
use core\services\manage\Core\TariffAssignmentManageService;
use Yii;
use core\entities\Core\TariffAssignment;
use backend\forms\core\TariffAssignmentSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TariffAssignmentController implements the CRUD actions for TariffAssignment model.
 */
class TariffAssignmentController extends Controller
{
    private $service;

    public function __construct($id, $module, TariffAssignmentManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'draft' => ['POST'],
                    'cancel' => ['POST'],
                    'activate' => ['POST'],
                    'apply-default' => ['POST'],
                    'apply-default-trial' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @param $hash_id
     * @param bool $overwrite
     * @param bool $set_date
     * @return Response
     */
    public function actionApplyDefaultTrial($tariff_id, $user_id, $hash_id, $overwrite = false, $set_date = true)
    {
        try {
            $this->service->applyDefaultTrial($tariff_id, $user_id, $hash_id, $overwrite, $set_date);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id]);
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @param $hash_id
     * @param bool $overwrite
     * @param bool $set_date
     * @return Response
     */
    public function actionApplyDefault($tariff_id, $user_id, $hash_id, $overwrite = false, $set_date = true)
    {
        try {
            $this->service->applyDefault($tariff_id, $user_id, $hash_id, $overwrite, $set_date);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id]);
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @param $hash_id
     * @return Response
     */
    public function actionActivate($tariff_id, $user_id, $hash_id)
    {
        try {
            $this->service->activate($tariff_id, $user_id, $hash_id, true);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id]);
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @param $hash_id
     * @return mixed
     */
    public function actionDraft($tariff_id, $user_id, $hash_id)
    {
        try {
            $this->service->draft($tariff_id, $user_id, $hash_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id]);
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @param $hash_id
     * @return mixed
     */
    public function actionCancel($tariff_id, $user_id, $hash_id)
    {
        try {
            $this->service->cancel($tariff_id, $user_id, $hash_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id]);
    }


    /**
     * Lists all TariffAssignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TariffAssignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TariffAssignment model.
     * @param integer $tariff_id
     * @param integer $user_id
     * @param $hash_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($tariff_id, $user_id, $hash_id)
    {
        $assignment = $this->findModel($tariff_id, $user_id, $hash_id);
        $form = new TariffAssignmentFormEditRenewal($assignment->tariff->default[0]);
        $orderItem = $assignment->orderItem;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->renewal($form, $tariff_id, $user_id, $hash_id);
                return $this->redirect(['view', 'tariff_id' => $assignment->tariff_id, 'user_id' => $assignment->user_id, 'hash_id' => $assignment->hash_id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        $renewal_items = RenewalOrderItem::find()->where(['product_user' => $assignment->user_id, 'product_hash' => $assignment->hash_id])->all();
        $additional_ip_items = AdditionalOrderItem::find()->where(['product_user' => $assignment->user_id, 'product_hash' => $assignment->hash_id])->all();

        return $this->render('view', [
            'model' => $assignment,
            'model_help' => $form,
            'orderItem' => $orderItem,
            'renewal_items' => $renewal_items,
            'additional_ip_items' => $additional_ip_items,
        ]);
    }

    /**
     * Updates an existing TariffAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $tariff_id
     * @param integer $user_id
     * @param $hash_id
     * @param int|null $default_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($tariff_id, $user_id, $hash_id, ?int $default_id = null)
    {
        $tariff = $this->findModel($tariff_id, $user_id, $hash_id);
        $form = new TariffAssignmentForm($tariff);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($tariff->tariff_id, $tariff->user_id, $hash_id, $form);
                return $this->redirect(['view', 'tariff_id' => $tariff->tariff_id, 'user_id' => $tariff->user_id, 'hash_id' => $tariff->hash_id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($default_id && $default = TariffDefaults::findOne($default_id)) {
            $form->attributes = $default->attributes;
        }

        return $this->render('update', [
            'tariff_model' => $form,
            'tariff' => $tariff,
        ]);
    }

    /**
     * Deletes an existing TariffAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $tariff_id
     * @param integer $user_id
     * @param $hash_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($tariff_id, $user_id, $hash_id)
    {
        $this->findModel($tariff_id, $user_id, $hash_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TariffAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $tariff_id
     * @param integer $user_id
     * @param $hash_id
     * @return TariffAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tariff_id, $user_id, $hash_id)
    {
        if (($model = TariffAssignment::findOne(['tariff_id' => $tariff_id, 'user_id' => $user_id, 'hash_id' => $hash_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('frontend', 'The requested page does not exist.'));
    }
}
