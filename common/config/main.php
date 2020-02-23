<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'sourceLanguage' => 'ru',
//    'timeZone' => 'Europe/Kiev',
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'common\bootstrap\SetUp'
    ],
    'components' => [
        'formatter' => [
            'class'           => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Kiev',
        ],
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//            'cachePath' => '@common/runtime/cache',
//        ],
        'cart' => [
            'class' => 'yii2mod\cart\Cart',
            'storageClass' => [
                'class' => 'yii2mod\cart\storage\DatabaseStorage',
                'deleteIfEmpty' => true
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\GettextMessageSource',
//                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],
    ],

];
