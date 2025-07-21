<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class OrderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionGetInfo()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id_order = Yii::$app->request->post('order_id');

        $sql = 'SELECT * FROM "Orders"
        WHERE id = '.$id_order;
        $Order = Yii::$app->db->createCommand($sql)->queryOne();

        $sql = 'SELECT c.*,co.order_id  
FROM "Clients" c
left join "Client_order" co on c.id =co.client_id 
where co.order_id = '.$id_order;
        $Client = Yii::$app->db->createCommand($sql)->queryOne();

        $sql = 'SELECT * FROM  "file_order" fo
        LEFT JOIN "file" f on f.id = fo.file_id
        WHERE fo.order_id = '.$id_order;

        $files = Yii::$app->db->createCommand($sql)->queryAll();

        return [
            'success' => true,
            'data' => $Order,
            'files' => $files,
            'data_client' => $Client
        ];
    }

    public function actionDeleteFile()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id_file = Yii::$app->request->post('id_file');

        Yii::$app->db->createCommand()->delete('file', ['id' => $id_file])->execute();

        Yii::$app->db->createCommand()->delete('file_order', ['file_id' => $id_file])->execute();

        return [
            'success' => true,
        ];
    }

    public function actionDeleteOrder()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            return ['success' => false, 'message' => 'Только AJAX-запросы'];
        }
        $selectedOrders = Yii::$app->request->post('selected_orders');

        if (empty($selectedOrders)) {
            return ['success' => false, 'message' => 'Не выбрано ни одного заказа'];
        }

        $ids = implode(', ', array_map('intval', $selectedOrders));


        Yii::$app->db->createCommand()->update('Orders', [
            'delete' => 1,
        ],'id in ('.$ids.')')->execute();


        return [
            'success' => true,
            'message' => 'Обработано заказов: ' . count($selectedOrders),
        ];

    }



}