<?php


namespace frontend\controllers\cabinet;


use core\entities\Core\Tariff;
use core\entities\User\User;
use core\repositories\NotFoundException;
use core\services\cabinet\ProfileService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class TariffsController extends Controller
{
    private $user;
    private $profileService;

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
                    'order' => ['POST'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, ProfileService $profileService, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->user = User::findOne(Yii::$app->user->id);
        $this->profileService = $profileService;
        $this->layout = 'cabinet';
    }

    public function actionOrder($id, $trial = false)
    {
        /** @var Tariff $tariff */
        $tariff = $this->findTariff($id);
        if ($this->user->issetTariff($tariff->id, $this->user->id))
            throw new NotFoundException();

        try {
            $this->profileService->addTariff($this->user, $tariff, $trial);
            Yii::$app->session->setFlash('success', 'Вы заказали тариф '.$tariff->name.'. Ожидайте активации');
            return $this->redirect(['cabinet/my-tariffs/view', 'id' => $tariff->id]);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect('index');

    }

    public function actionIndex()
    {
        $tariffDataProvider = new ActiveDataProvider([
            'query' => Tariff::find()->active(),
        ]);

        return $this->render('index', [
            'tariffDataProvider' => $tariffDataProvider
        ]);
    }

    public function actionView($id)
    {
        $tariff = $this->findTariff($id);

        return $this->render('view', [
            'tariff' => $tariff,
            'user' => $this->user
        ]);
    }

    public function findTariff($id)
    {
        $tariff = Tariff::find()->where(['id' => $id])->active()->one();
        if (!$tariff)
            throw new NotFoundException();

        return $tariff;
    }
}
