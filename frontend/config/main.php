<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'sourceLanguage' => 'ru',
    'name' => 'RemProxy',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'languages'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'languages' => [
            'class' => 'common\modules\languages\Module',
            //Языки используемые в приложении
            'languages' => [
                'En' => 'en',
                'Ru' => 'ru',
            ],
            'default_language' => 'ru', //основной язык (по-умолчанию)
            'show_default' => true, //true - показывать в URL основной язык, false - нет
        ],
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
            ],
        ],
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV2' => '6Ldd6s4UAAAAAFfYT1FuiDTen2v_kl2sEyctD6v0',
            'secretV2' => '6Ldd6s4UAAAAAKfkYLjmOCuwM50k3mXTjYwITYMM',
            'siteKeyV3' => '',
            'secretV3' => '',
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => $params['cookieValidationKey'],
            'baseUrl' => '',
            'class' => 'common\components\Request'
        ],
        'user' => [
            'identityClass' => 'core\entities\User\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => $params['cookieDomain']],
            'loginUrl'=>array('/login'),
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
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
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('frontendUrlManager');
        },
    ],
    'as access' => [
        'class' => \yii2mod\rbac\filters\AccessControl::className(),
        'except' => ['news/*', 'site/*', 'contact/*', 'auth/auth/*', 'auth/reset/*', 'auth/reset/*', 'auth/signup/*', 'coin/webhook'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['user'],
            ],
        ],
    ],
    'params' => $params,
];
