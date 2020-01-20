<?php


namespace frontend\controllers\cabinet;


use core\entities\Core\Tariff;
use core\entities\Core\TariffAssignment;
use core\entities\User\User;
use core\forms\manage\Core\TariffAssignmentEditIpsForm;
use core\repositories\NotFoundException;
use core\services\manage\Core\TariffAssignmentManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
            ]
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

    public function actionTariff($id, $u)
    {
        $tariff = $this->findTariffAssignment($id, $u);

        return $this->render('tariff_item', [
            'tariff' => $tariff
        ]);
    }

    public function actionEdit($id, $u)
    {
        $tariff = $this->findTariffAssignment($id, $u);
        $form = new TariffAssignmentEditIpsForm($tariff);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editIPs($id, $u, $form);
                return $this->redirect(['tariff', 'id' => $id, 'u' => $u]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit', [
            'tariff' => $tariff,
            'model' => $form,
        ]);
    }

    /**
     * @param $id
     * @param $u
     * @return TariffAssignment|Response|null
     */
    public function findTariffAssignment($id, $u)
    {
        $tariff = TariffAssignment::findOne([$id, $u]);
        if (!$this->user->issetTariff($id, $u))
            throw new NotFoundException();

        return $tariff;
    }
}
