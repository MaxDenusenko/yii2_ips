<?php

use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'sourceLanguage' => 'ru',
    'name' => 'RemProxy',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@staticRoot' => $params['staticPath'],
        '@static' => $params['staticHostInfo'],
    ],
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'languages'],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
        ],
        'languages' => [
            'class' => 'common\modules\languages\Module',
            //Языки используемые в приложении
            'languages' => [
                'En' => 'en',
                'Ru' => 'ru',
            ],
            'default_language' => 'ru', //основной язык (по-умолчанию)
            'show_default' => false, //true - показывать в URL основной язык, false - нет
        ],
    ],
    'components' => [
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '6Ldd6s4UAAAAAFfYT1FuiDTen2v_kl2sEyctD6v0',
            'secretV2' => '6Ldd6s4UAAAAAKfkYLjmOCuwM50k3mXTjYwITYMM',
            'siteKeyV3' => '',
            'secretV3' => '',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => $params['cookieValidationKey'],
            'baseUrl' => '', // убрать frontend/web
            'class' => 'common\components\Request'
        ],
        'user' => [
            'identityClass' => 'core\entities\User\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => $params['cookieDomain']],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => '_session',
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
                'httpOnly' => true
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations'=>[
                'frontend*'=>[
                    'class'=>yii\i18n\GettextMessageSource::className(),
//                    'basePath'=>'@frontend/messages',
//                    'sourceLanguage'=>'ru-RU',
//                    'fileMap'=>[
//                        'frontend'=>'frontend.php',
//                        'frontend/error'=>'frontend_error.php',
//                    ]
                ],
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'backendUrlManager' => require __DIR__ . '/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('backendUrlManager');
        },
    ],
    'as access' => [
        'class' => \yii2mod\rbac\filters\AccessControl::className(),
        'denyCallback' => function($rule, $action)
        {
            exit();
        },
        'rules' => [
            [
                'allow' => true,
                'roles' => ['admin'],
            ],
        ],
    ],
    'params' => $params,
];
