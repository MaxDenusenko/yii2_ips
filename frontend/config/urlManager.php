<?php

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:about>' => 'site/<_a>',
        'contact' => 'contact/index',
        'basket' => 'basket/index',
        'faq' => 'site/faq',
        'signup' => '/auth/signup/index',
        'signup/<_a:[\w-]+>' => '/auth/signup/<_a>',
        '<_a:login|logout>' => '/auth/auth/<_a>',

        'cabinet' => 'cabinet/default/index',
        'cabinet/<_c:[\w\-]+>' => 'cabinet/<_c>/index',
        'cabinet/<_c:[\w\-]+>/<id:\d+>' => 'cabinet/<_c>/view',
        'cabinet/<_c:[\w\-]+>/edit/<id:\d+>' => 'cabinet/<_c>/edit',
        'cabinet/<_c:[\w\-]+>/delete/<id:\d+>' => 'cabinet/<_c>/delete',
        'cabinet/<_c:[\w\-]+>/renewal/<id:\d+>' => 'cabinet/<_c>/renewal',
        'cabinet/<_c:[\w\-]+>/order/<id:\d+>' => 'cabinet/<_c>/order',
        'cabinet/<_c:[\w\-]+>/<_a:[\w-]+>' => 'cabinet/<_c>/<_a>',
        'cabinet/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => 'cabinet/<_c>/<_a>',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];
