<?php
namespace app\modules\admin\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $params = [];
        $params['title'] = 'Панель администратора';
        return $this->render(
            'index',['params' => $params]
        );
    }

    public function actionTest()
    {
        return 'Тест работает!';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],  // Удаление только через POST
                ],
            ],
        ];
    }
}