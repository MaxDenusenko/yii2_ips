<?php


namespace frontend\controllers;


use core\entities\Core\Basket;
use core\forms\manage\Core\AddToBasketForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BasketController extends Controller
{
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
                    'add' => ['POST'],
                    'update' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {

        $basket = (new Basket())->getBasket();
        return $this->render('index', ['basket' => $basket]);
    }

    public function actionAdd() {

        $basket = new Basket();
        $adToBasketForm = new AddToBasketForm();

        if ($adToBasketForm->load(Yii::$app->request->post()) && $adToBasketForm->validate()) {

            $basket->addToBasket($adToBasketForm);

            if (Yii::$app->request->isAjax) {

                $this->layout = false;
                $content = $basket->getBasket();
                return $this->render('modal', ['basket' => $content]);
            } else {

                Yii::$app->session->setFlash('success', 'Товар добавлен в корзину');
                return $this->redirect(['basket/index']);
            }

        } else {
            Yii::$app->session->setFlash('error', "Не удалось добавить товар в корзину");
        }

        return $this->goBack();
    }

    public function actionRemove($id) {

        $basket = new Basket();
        $basket->removeFromBasket($id);

        if (Yii::$app->request->isAjax) {

            $this->layout = false;
            $content = $basket->getBasket();
            return $this->render('modal', ['basket' => $content]);
        }

        return $this->redirect(['basket/index']);
    }

    public function actionClear() {

        $basket = new Basket();
        $basket->clearBasket();

        if (Yii::$app->request->isAjax) {

            $this->layout = false;
            $content = $basket->getBasket();
            return $this->render('modal', ['basket' => $content]);
        }

        return $this->redirect(['basket/index']);

    }

    public function actionUpdate() {

        $data = Yii::$app->request->post();
        if (!isset($data['count'])) {
            return $this->redirect(['basket/index']);
        }

        $basket = new Basket();
        $basket->updateBasket($data);

        if (Yii::$app->request->isAjax) {

            $this->layout = false;
            $content = $basket->getBasket();
            return $this->render('modal', ['basket' => $content]);

        } else {

            return $this->redirect(['basket/index']);
        }
    }
}
