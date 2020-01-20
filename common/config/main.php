<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'sourceLanguage' => 'ru',
    'timeZone' => 'Europe/Kiev',
    'language' => 'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'common\bootstrap\SetUp'
    ],
    'components' => [
        'formatter' => [
            'class'           => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['admin', 'user'],
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],
    ],

];
