<?php


namespace frontend\controllers\cabinet;


use core\entities\User\User;
use core\forms\user\ProfileEditForm;
use core\services\cabinet\ProfileService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SettingController extends Controller
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
            ]
        ];
    }

    public function __construct($id, $module, ProfileService $profileService, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->user = User::findOne(Yii::$app->user->id);
        $this->layout = 'cabinet';
        $this->profileService = $profileService;
    }

    public function actionIndex()
    {
        return $this->render('index',[
            'user' => $this->user,
        ]);
    }

    public function actionEdit()
    {
        $form = new ProfileEditForm($this->user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->profileService->edit($this->user->id, $form);
                Yii::$app->session->setFlash('success', 'Данные аккаунта обновлены');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $form,
            'user' => $this->user,
        ]);
    }
}
