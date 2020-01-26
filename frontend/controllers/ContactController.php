<?php


namespace frontend\controllers;


use core\entities\Fragments;
use core\forms\ContactForm;
use core\services\contact\ContactService;
use Yii;
use yii\web\Controller;

class ContactController extends Controller
{
    private $contactService;

    public function __construct(
        $id,
        $module,
        ContactService $contactService,
        $config = []
    )
    {
        $this->contactService = $contactService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->contactService->send($form);
                Yii::$app->session->setFlash('success', 'Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('success', 'При отправке вашего сообщения произошла ошибка.');
            }
            return $this->refresh();
        }

        $about = Fragments::find()->where(['name' => 'about'])->one();

        return $this->render('index', [
            'model' => $form,
            'content' => $about,
        ]);
    }
}
