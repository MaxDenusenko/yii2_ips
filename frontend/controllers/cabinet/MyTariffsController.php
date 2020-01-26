<?php


namespace frontend\controllers\cabinet;


use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
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
                    'delete' => ['POST'],
                    'renewal' => ['POST'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, TariffAssignmentManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->user = User::findOne(\Yii::$app->user->id);
        $this->service = $service;
        $this->layout = 'cabinet';
    }

    public function actionIndex()
    {
        $tariffDataProvider = new ActiveDataProvider([
            'query' => $this->user->getTariffAssignments(),
        ]);

        $infoAr = $this->service->checkDateTariff($this->user);

        return $this->render('index', [
            'tariffDataProvider' => $tariffDataProvider,
            'infoAr' => $infoAr
        ]);
    }

    public function actionView($id)
    {
        try {
            $tariff = $this->findTariffAssignment($id);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        return $this->render('tariff_item', [
            'tariff' => $tariff
        ]);
    }

    public function actionEdit($id)
    {
        try {
            $tariff = $this->findTariffAssignment($id);
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        $form = new TariffAssignmentEditIpsForm($tariff);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editIPs($id, $this->user->id, $form);
                Yii::$app->session->setFlash('success', 'Информация была обновлена!');
                return $this->redirect(['view', 'id' => $id]);
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

    public function actionRenewal($id)
    {
        try {
            $tariff = $this->findTariffAssignment($id);
            if (!$tariff->isDeactivated()) {
                throw new NotFoundException('Тариф не найден');
            }
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        try {
            $this->service->renewalRequest($id, $this->user->id);
            Yii::$app->session->setFlash('success', 'Запрос на продление тарифа отправлен!');
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        try {
            $tariff = $this->findTariffAssignment($id)->delete();
            Yii::$app->session->setFlash('warning', 'Тариф отменен!');
        } catch (NotFoundException $e) {
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return TariffAssignment|Response|null
     */
    public function findTariffAssignment($id)
    {
        $tariff = TariffAssignment::findOne(['tariff_id' => $id, 'user_id' => $this->user->id]);
        if (!$this->user->issetTariff($id, $this->user->id))
            throw new NotFoundException('Тариф не найден');

        return $tariff;
    }
}
