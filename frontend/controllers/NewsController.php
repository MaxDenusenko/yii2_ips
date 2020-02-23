<?php


namespace frontend\controllers;


use core\entities\News;
use core\repositories\NotFoundException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class NewsController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'order' => ['POST'],
                ],
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
        $news = News::find()->all();

        return $this->render('index', [
            'news' => $news,
        ]);
    }

    public function actionView($id)
    {
        $news = $this->findNews($id);

        return $this->render('view', [
            'news' => $news,
        ]);
    }

    public function findNews($id)
    {
        $news = News::find()->where(['id' => $id])->one();
        if (!$news)
            throw new NotFoundException();

        return $news;
    }
}
