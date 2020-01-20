<?php

namespace backend\controllers\core;

use core\entities\Core\TariffDefaults;
use core\forms\manage\Core\TariffAssignmentForm;
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
                    'activate' => ['POST'],
                    'activate-trial' => ['POST'],
                ],
            ],
        ];
    }

    public function actionActivateTrial($tariff_id, $user_id)
    {
        try {
            $this->service->activateTrial($tariff_id, $user_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id]);
    }
    /**
     * @param $tariff_id
     * @param $user_id
     * @return Response
     */
    public function actionActivate($tariff_id, $user_id)
    {
        try {
            $this->service->activate($tariff_id, $user_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id]);
    }

    /**
     * @param $tariff_id
     * @param $user_id
     * @return mixed
     */
    public function actionDraft($tariff_id, $user_id)
    {
        try {
            $this->service->draft($tariff_id, $user_id);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'tariff_id' => $tariff_id, 'user_id' => $user_id]);
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
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($tariff_id, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($tariff_id, $user_id),
        ]);
    }

    /**
     * Updates an existing TariffAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $tariff_id
     * @param integer $user_id
     * @param null $default_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($tariff_id, $user_id, ?int $default_id = null)
    {
        $tariff = $this->findModel($tariff_id, $user_id);
        $form = new TariffAssignmentForm($tariff);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($tariff->tariff_id, $tariff->user_id, $form);
                return $this->redirect(['view', 'tariff_id' => $tariff->tariff_id, 'user_id' => $tariff->user_id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($default_id && $default = TariffDefaults::findOne($default_id)) {
            $form->attributes = $default->attributes;
        }

        $dataProviderDefaults = new ActiveDataProvider([
            'query' => TariffDefaults::find(),
            'pagination' => false,
        ]);

        return $this->render('update', [
            'tariff_model' => $form,
            'tariff' => $tariff,
            'dataProviderDefaults' => $dataProviderDefaults,
        ]);
    }

    /**
     * Deletes an existing TariffAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $tariff_id
     * @param integer $user_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($tariff_id, $user_id)
    {
        $this->findModel($tariff_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TariffAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $tariff_id
     * @param integer $user_id
     * @return TariffAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($tariff_id, $user_id)
    {
        if (($model = TariffAssignment::findOne(['tariff_id' => $tariff_id, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
