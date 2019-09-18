<?php
return [
    'id' => 'micro-app',
    // basePath (базовый путь) приложения будет каталог `micro-app`
    'basePath' => __DIR__,
    // это пространство имен где приложение будет искать все контроллеры
    'controllerNamespace' => 'micro\controllers',
    // установим псевдоним '@micro', чтобы включить автозагрузку классов из пространства имен 'micro'
    'aliases' => [
        '@micro' => __DIR__,
    ],

    'components' => [

    	'db' => [
	    'class' => 'yii\db\Connection',
	    'dsn' => 'mysql:host=localhost;dbname=test-micro-yii2',
	    'username' => 'homestead',
	    'password' => 'secret',
	    'charset' => 'utf8',
	],
    ]
];