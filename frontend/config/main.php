<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
        'layout'=>false,
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'class'=>'\yii\web\User',
            'loginUrl'=>'member/login',//我自己的登录页面
            'identityClass' => 'frontend\models\Member',//认证配置
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix'=>'.html',//网页静态化
            'rules' => [
            ],
        ],


        'sms' => [
            'class'=>\frontend\aliyun\SmsHander::class,
            'ak'=>'LTAI2IbetQSkUACb',//ak
            'sk'=>'4wzzZl3FAL30XEBZ7dTSSH4BSFGk6Z',//sk
            'sign'=>'科玛学习站',//阿里云上的申请签名
            'templateCode'=>'SMS_126950036'//模板号
        ]

    ],
    'params' => $params,
];
