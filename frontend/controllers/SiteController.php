<?php
namespace frontend\controllers;

use core\entities\Core\Tariff;
use core\entities\Faq;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii2mod\rbac\filters\AccessControl;


class SiteController extends Controller
{
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
        $tariffDataProvider = new ActiveDataProvider([
            'query' => Tariff::find()->active(),
        ]);

        return $this->render('index', [
            'tariffDataProvider' => $tariffDataProvider
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
