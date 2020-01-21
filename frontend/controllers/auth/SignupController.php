<?php


namespace frontend\controllers\auth;


use core\forms\auth\ResendVerificationEmailForm;
use core\forms\auth\VerifyEmailForm;
use core\services\auth\EmailVerification;
use Yii;
use core\forms\auth\SignupForm;
use core\services\auth\SignupService;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use DomainException;
use yii\web\Response;

class SignupController extends Controller
{
    private $signupService;
    private $emailVerification;

    public function __construct(
        $id,
        $module,
        SignupService $signupService,
        EmailVerification $emailVerification,
        $config = []
    )
    {
        $this->signupService = $signupService;
        $this->emailVerification = $emailVerification;
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                if ($this->signupService->signup($form)) {
                    Yii::$app->session->setFlash('success', 'Спасибо за регистрацию. Пожалуйста, проверьте свой почтовый ящик для подтверждения по электронной почте.');
                    return $this->goHome();
                }
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $this->emailVerification->validateToken($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new VerifyEmailForm();
        $form->token = $token;
        if ($form->validate()) {
            try {
                $this->emailVerification->verifyEmail($token);
                Yii::$app->session->setFlash('success', 'Ваш email был подтвержден! Вам нужно связаться с администратором для активации аккаунта');
                return $this->redirect(['/login']);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $form = new ResendVerificationEmailForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {

            try {
                $this->emailVerification->sendEmail($form);
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для дальнейших инструкций.');
                return $this->goHome();
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('resendVerificationEmail', [
            'model' => $form
        ]);
    }
}
