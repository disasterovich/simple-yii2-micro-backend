<?php

namespace micro\controllers;

use app\models\RegisterForm;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use micro\models\LoginForm;
use micro\models\User;
use yii\web\HttpException;

class UserController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    public function behaviors()
    {
    $behaviors = parent::behaviors();

    $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
		'except' => ['options','login','register'],
	];

    $behaviors['corsFilter'] = [
	'class' => \yii\filters\Cors::className(),
        'cors' => [
        'Origin' => ['*'], //['http://localhost:8080'], //откуда приходят запросы
		'Access-Control-Request-Headers' => ["authorization", "content-type"],
		'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Per-Page', 'X-Pagination-Total-Count'],
		'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
//		'Access-Control-Allow-Credentials' => true,
        	],
	];

    $behaviors['contentNegotiator'] = [
        'class' => ContentNegotiator::className(),
        'formats' => [
            'application/json' => Response::FORMAT_JSON,
        ],
    ];

    return $behaviors;
    }

    public function actionLogin()
	{
        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post(),'') && $model->validate()) {

            $user = User::findOne(['email' => $model->email]);

            if ($user) {
                $password_hash = User::hashPassword($model->password, $user->salt);

                if ($password_hash === $user->password) {
                    return ['status' => 'success',
                        'user_data' => [
                            'token' => $user->access_token,
                            'id'    => $user->id,
                            'email' => $user->email,
                            'name'  => $user->name,
                        ]
                    ];
                    }
                else {
                    return [
                        'status' => 'error',
                        'errors' => [
                            'password' => ['Неверный пароль',]
                        ]
                    ];
                }
            }
            else {
                return [
                    'status' => 'error',
                    'errors' => [
                        'password' => ['Пользователь не найден',]
                    ]
                ];
            }
        }
        else {
                return [
                    'status' => 'error',
                    'errors' => $model->getErrors()
                ];
        }

	}

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(\Yii::$app->request->post(), '') && $model->validate()) {

            if ( User::find()->where(['email' => $model->email])->exists() ) {
                $model->addError('email', 'Пользователь с таким email уже есть в базе');

                return [
                    'status' => 'error',
                    'errors' => $model->getErrors()
                ];
            }

            $user = new User();
            $user->name = $model->name;
            $user->email = $model->email;
            $user->save(false);

            return ['status' => 'success'];
        }
        else {
            return [
                'status' => 'error',
                'errors' => $model->getErrors()
            ];
        }

    }
}