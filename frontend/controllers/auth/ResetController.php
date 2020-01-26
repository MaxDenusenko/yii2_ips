<?php


namespace frontend\controllers\auth;


use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;
use core\services\auth\PasswordResetService;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use Yii;
use DomainException;

class ResetController extends Controller
{
    private $passwordResetService;

    public function __construct(
        $id,
        $module,
        PasswordResetService $passwordResetService,
        $config = []
    )
    {
        $this->passwordResetService = $passwordResetService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();

        try {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $this->passwordResetService->request($form);
                Yii::$app->session->setFlash('success',  \Yii::t('frontend', 'Check your email for further instructions.'));
                return $this->goHome();
            }
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $this->passwordResetService->validateToken($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm();

        try {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $this->passwordResetService->resetPassword($token, $form);
                Yii::$app->session->setFlash('success', \Yii::t('frontend', 'New password saved.'));
                return $this->goHome();
            }
        } catch (\Exception $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', \Yii::t('frontend', $e->getMessage()));
        }

        return $this->render('resetPassword', [
            'model' => $form,
        ]);
    }
}
