<?php


namespace frontend\controllers\cabinet;


use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\entities\User\User;
use core\forms\manage\Core\AddToBasketForm;
use core\forms\manage\Core\OrderForm;
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

    public function actionIndex()
    {
        $other_tariffs = Tariff::find()->noCategory()->all();
        $category_root = CategoryTariffs::find()->roots()->one();
        $categories = $category_root->populateTree();

        $orderForm = new OrderForm();

        return $this->render('index', [
            'other_tariffs' => $other_tariffs,
            'orderForm' => $orderForm,
            'categories' => $categories->children
        ]);
    }

    public function actionView($id)
    {
        $tariff = $this->findTariff($id);
        $orderForm = new OrderForm();

        return $this->render('view', [
            'tariff' => $tariff,
            'user' => $this->user,
            'orderForm' => $orderForm,
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
