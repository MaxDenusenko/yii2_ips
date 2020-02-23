<?php
namespace frontend\controllers;

use core\entities\Core\CategoryTariffs;
use core\entities\Core\Tariff;
use core\entities\Faq;
use core\entities\News;
use core\forms\manage\Core\OrderForm;
use core\services\NestedSetsTree;
use yii\data\ActiveDataProvider;
use yii\web\Controller;


class SiteController extends Controller
{
    public $nestedSetsTree;

    public function __construct($id, $module, NestedSetsTree $nestedSetsTree, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->nestedSetsTree = $nestedSetsTree;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $other_tariffs = Tariff::find()->noCategory()->all();
        $category_root = CategoryTariffs::find()->roots()->one();
        $categories = $category_root->populateTree();
        $news = News::find()->all();

        $orderForm = new OrderForm();

        return $this->render('index', [
            'other_tariffs' => $other_tariffs,
            'orderForm' => $orderForm,
            'categories' => $categories->children,
            'news' => $news,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @return mixed
     */
    public function actionFaq()
    {
        $faqDataProvider = new ActiveDataProvider([
            'query' => Faq::find(),
        ]);

        return $this->render('faq', [
            'faqDataProvider' => $faqDataProvider
        ]);
    }
}
