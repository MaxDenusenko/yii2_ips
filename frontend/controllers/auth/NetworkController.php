<?php


namespace frontend\controllers\auth;


use core\services\auth\NetworkService;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use DomainException;
use Yii;
use yii\web\Response;

class NetworkController extends Controller
{
    private $service;

    public function __construct(
        $id,
        $module,
        NetworkService $service,
        $config = []
    )
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthAttach'],
                'successUrl' => Url::to(['cabinet/default/index'])
            ]
        ];
    }

    /**
     * @param ClientInterface $client
     */
    public function onAuthAttach(ClientInterface $client): void {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $this->service->attach(Yii::$app->user->id, $network, $identity);
            Yii::$app->session->setFlash('success', 'Network is successfully attached.');
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    /**
     * @param ClientInterface $client
     * @return Response
     */
    public function onAuthSuccess(ClientInterface $client): Response
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $user = $this->service->auth($network, $identity);
            Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            return $this->goBack();
        } catch (DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}
