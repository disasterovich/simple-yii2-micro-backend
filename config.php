<?php

return [
    'id' => 'micro-app',
    'language' => 'ru',
    // basePath (базовый путь) приложения будет каталог `micro-app`
    'basePath' => __DIR__,

    // это пространство имен где приложение будет искать все контроллеры
    'controllerNamespace' => 'micro\controllers',
    // установим псевдоним '@micro', чтобы включить автозагрузку классов из пространства имен 'micro'
    'aliases' => [
        '@micro' => __DIR__,
    ],
    'defaultRoute' => 'order', //default route

    'timeZone' => 'Europe/Moscow',

    'components' => [

    	'db' => [
	    'class' => 'yii\db\Connection',
	    'dsn' => 'mysql:host=localhost;dbname=test-micro-yii2',
	    'username' => 'homestead',
	    'password' => 'secret',
	    'charset' => 'utf8',
	],

        'user' => [
//	    'class' => 'yii\web\User',
            'identityClass' => 'micro\models\User',
            'enableSession' => false,
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
//            'cookieValidationKey' => '5U7amXWhGLEDfmgFEDu-4XwS4pRhVdnj',
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false,

            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
            ],
        ],

	'urlManager' => [
	    'enablePrettyUrl' => true,
	    'enableStrictParsing' => true,
	    'showScriptName' => false,
	    'rules' => [

            //для работы с заказами
            'PUT,PATCH api/orders/<id:\d+>' => 'order/update',
            'DELETE api/orders/<id:\d+>' => 'order/delete',
            'GET,HEAD api/orders/<id:\d+>' => 'order/view',
            'POST api/orders' => 'order/create',
            'GET,HEAD api/orders' => 'order/index',

            'OPTIONS api/orders/<id:\d+>' => 'order/options',
            'OPTIONS api/orders' => 'order/options',

            //для работы с пользователями
            'POST api/users/login' => 'user/login',
            'POST api/users/register' => 'user/register',

            'api/users/<id:\d+>' => 'user/options',
            'api/users' => 'user/options',
            'api/users/login' => 'user/options',
            'api/users/register' => 'user/options',
	    ],
	],
        
    ]
];
