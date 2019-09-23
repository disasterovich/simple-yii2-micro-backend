<?php

namespace micro\controllers;

use yii;
use micro\models\Order;
use micro\models\OrderForm;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\HttpException;

class OrderController extends \yii\rest\Controller
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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //Включить для аутентификации по токену
/*
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['options','login'],
        ];
*/

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

    /**
     * @return yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $searchModel = new \micro\models\OrderSearch();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        $modelForm = new OrderForm();

        if ($modelForm->load(\Yii::$app->request->post(),'') && $modelForm->save()) {
            return [
                'status' => 'success',
            ];
        }

        return [
            'status' => 'error',
            'errors' => $modelForm->getErrors()
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelForm = new OrderForm($model);

        if ($modelForm->load(\Yii::$app->request->post(),'') && $modelForm->save()) {
            return [
                'status' => 'success',
            ];
        }

        return [
            'status' => 'error',
            'errors' => $modelForm->getErrors()
        ];

    }

    /**
     * @param $id
     * @return array
     * @throws HttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return [
            'status' => 'success',
            'order_data' => [
                'id'                => $model->id,
                'clientName'        => $model->client->name,
                'clientPhone'       => $model->client->phone,
                'departureAddress'  => $model->departure->address,
                'departureFrom'     => $model->departure->from,
                'departureTo'       => $model->departure->to,
            ]
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws HttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            return [
                'status' => 'success',
            ];
        }

        return [
            'status' => 'error',
            'errors' => $model->getErrors()
        ];
    }

    /**
     * @param $id
     *
     * @return array|Order|null
     * @throws HttpException
     */
    protected function findModel($id)
    {
        $model = Order::findOne($id);

        if (!$model) {
            throw new HttpException(404, 'Заказ не найден.');
        }

        return $model;
    }

}