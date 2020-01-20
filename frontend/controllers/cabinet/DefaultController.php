<?php


namespace frontend\controllers\cabinet;


use core\entities\User\User;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    private $user;

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

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->user = User::findOne(\Yii::$app->user->id);
    }

    public function actionIndex()
    {
        return $this->redirect('cabinet/tariffs');
    }
}
