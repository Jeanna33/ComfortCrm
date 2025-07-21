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
use app\models\Event;

class CalendarController extends Controller
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

        return $this->render('index');
    }

    public function actionGetEvent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $update_event = Yii::$app->request->post('update_event');

        $All_event = [];
        if($update_event == 1)
        {
            $sql = 'SELECT * FROM "Orders" WHERE delete is null or delete = 0';
            $rows = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($rows as $item => $val)
            {
                if($val['date_begin'] != null)
                {
                    $new = [
                        'id' => $val['id'],
                        'title' => $val['name'],
                        'start' => $val['date_begin'],
                        'end' => $val['date_end'],
                        'color' => '#dc6f35',
                        'description' => $val['info_about_order'],
                        'type' => 1
                    ];

                    $All_event[$val['id']] = $new;
                }
            }

            $sql = 'SELECT * FROM "Event" WHERE delete is null or delete = 0';
            $rows = Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($rows as $item => $val)
            {
                    $new = [
                        'id' => $val['id'],
                        'title' => $val['title_event'],
                        'start' => $val['start_event'],
                        'end' => $val['end_event'],
                        'color' => '#4977cc',
                        'description' => $val['description'],
                        'type' => 0
                    ];
                    $All_event[$val['id']] = $new;
            }
        }

        return [
            'success' => true,
            'message' => $All_event,
        ];
    }

    public function actionSaveEvent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $event = Yii::$app->request->post('event');

        if($event['type'] == 0)
        {
            Yii::$app->db->createCommand()->insert('Event', [
                'description' => $event['description'],
                'end_event' => $event['end_event'],
                'start_event' =>  $event['start_event'],
                'title_event' =>  $event['title_event'],
            ])->execute();

            return [
                'success' => true,
                'message' => 0,
            ];
        }
        else if($event['type'] == 1)
        {
            Yii::$app->db->createCommand()->insert('Orders', [
                'info_about_order' => $event['description'],
                'date_end' => $event['end_event'],
                'date_begin' =>  $event['start_event'],
                'name' =>  $event['title_event'],
            ])->execute();

            return [
                'success' => true,
                'message' => 1,
            ];
        }

        return [
            'success' => true,
            'message' => $event,
        ];
    }

    public function actionUpdateEvent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $event = Yii::$app->request->post('event');

        if($event['type'] == 0)
        {
            Yii::$app->db->createCommand()->update('Event', [
                'description' => $event['description'],
                'end_event' => $event['end_event'],
                'start_event' =>  $event['start_event'],
                'title_event' =>  $event['title_event'],
            ],'id='.$event['id'])->execute();
        }
        else if($event['type'] == 1)
        {
            Yii::$app->db->createCommand()->update('Orders', [
                'info_about_order' => $event['description'],
                'date_end' => $event['end_event'],
                'date_begin' =>  $event['start_event'],
                'name' =>  $event['title_event']
            ], 'id='.$event['id'])->execute();
        }

        return [
            'success' => true,
            'message' => $event['type'],
        ];

    }

    public function actionDeleteEvent()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $delete = Yii::$app->request->post('delete');
        $id_db = Yii::$app->request->post('id_db');
        $type = Yii::$app->request->post('type');

        $info = [
            'delete' => $delete,
            'id_db' => $id_db,
            'type' => $type];

        if($delete == 1)
        {
            if($type == 1)
            {
                Yii::$app->db->createCommand()->update('Orders', [
                    'delete' => 1],
                    'id='.$id_db)->execute();

                return [
                    'success' => true,
                    'message' => 'Задача удалена 1',
                ];
            }
            if($type == 0)
            {
                Yii::$app->db->createCommand()->update('Event',[
                    'delete' =>1],
                    'id='.$id_db)->execute();

                return [
                    'success' => true,
                    'message' => 'Задача удалена 0',
                ];
            }
        }

        return [
            'success' => true,
            'message' => $info,
        ];
    }



}
