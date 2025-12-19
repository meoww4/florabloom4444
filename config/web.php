<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Flora Bloom',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    'components' => [

        // Компонент request (нужен для REST API)
        'request' => [
            'cookieValidationKey' => 'Maria1234',
            'parsers' => [
                // Позволяет принимать JSON-запросы
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        // Компонент пользователя (Bearer Token, без сессий)
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => false,
        ],

        // Обработчик ошибок (для сайта)
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
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

        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,

            'enableStrictParsing' => false,

            'showScriptName' => false,

            'rules' => [

                // REST API
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'product'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'category'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'order'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'order-item'],

                // Авторизация
                'POST login' => 'user/login',
                'POST register' => 'user/create',
            ],
        ],

        'response' => [
            'class' => 'yii\web\Response',
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;

