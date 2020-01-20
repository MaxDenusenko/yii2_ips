<?php

$config = [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => '50801c8cd3fda2d3a8dd',
                    'clientSecret' => '67ecdeb6c4a2fecf533e4a4220659c71b4ac21ea',
                ],
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '77j2geolu7bvc6',
                    'clientSecret' => 'WnzG7Dug05He2t4p',
                ],
            ],
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
