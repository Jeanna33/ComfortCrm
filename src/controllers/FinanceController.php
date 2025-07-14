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

class FinanceController extends Controller
{
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

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $action = Yii::$app->request->post('action');

        $sql = 'select SUM(sum) from "Orders" o 
where o.currency ='."'rub'".' and is_active = false';
        $order_rub = Yii::$app->db->createCommand($sql)->queryOne();

        if($action == 'save')
        {
            $all_sum = Yii::$app->request->post('all_sum');
            $minus = Yii::$app->request->post('minus');

            Yii::$app->db->createCommand()->insert('finance', [
                'sum' => $all_sum,
                'minus' => $minus,
                'date' => date('Y-m-d H:i:s')
            ])->execute();

        }

        $All_finance = [];
        $sql = 'SELECT * FROM finance';
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($rows as $item => $val)
        {
            $All_finance[$val['id']] = $val;
        }

        return $this->render('index',
            [
                'sum' => $order_rub,
                'All_finance' => $All_finance
            ]);
    }

    public function actionGetGraph()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $update_graph = Yii::$app->request->post('update_graph');
        $All_finance = [];
        if($update_graph == 1)
        {
            $sql = 'SELECT * FROM finance';
            $rows = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($rows as $item => $val)
            {
                $All_finance[$val['id']] = $val;
            }
        }

        return [
            'success' => true,
            'message' => $All_finance,
        ];
    }



}