<?php


namespace common\bootstrap;


use frontend\controllers\cabinet\NetworkController;
use core\services\auth\EmailVerification;
use core\services\auth\PasswordResetService;
use core\services\auth\SignupService;
use core\services\contact\ContactService;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\Instance;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use($app) {
            return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class, [], [
            [$app->params['supportEmail'] => $app->name . ' robot'],
            $app->name,
        ]);

        $container->setSingleton(ContactService::class, [], [
            $app->params['supportEmail'],
            $app->params['adminEmail'],
        ]);

        $container->setSingleton(SignupService::class, [], [
            [$app->params['supportEmail'] => $app->name . ' robot'],
            $app->name,
        ]);

        $container->setSingleton(EmailVerification::class, [], [
            [$app->params['supportEmail'] => $app->name . ' robot'],
            $app->name,
        ]);

    }
}
