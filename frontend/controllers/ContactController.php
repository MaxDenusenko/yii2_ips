<?php


namespace frontend\controllers;


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
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible');
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('success', 'We was an error sending your message');
            }
            return $this->refresh();
        }
        return $this->render('index', [
            'model' => $form,
        ]);
    }
}
