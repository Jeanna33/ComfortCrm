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

class ClientsController extends Controller
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

        if($action == 'save_client')
        {
            $name_company = Yii::$app->request->post('name_company');
            $director = Yii::$app->request->post('director_name');
            $manager = Yii::$app->request->post('manager_name');
            $phone = Yii::$app->request->post('phone');
            $email = Yii::$app->request->post('email');

            Yii::$app->db->createCommand()->insert('Clients', [
                'company_name' => $name_company,
                'phone' => $phone,
                'email' => $email,
                'director_name' => $director
            ])->execute();

            $client_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->insert('Managers', [
                'full_name' => $manager,
            ])->execute();

            $manager_id = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->insert('Managers_clients', [
                'client_id' => $client_id,
                'manager_id' => $manager_id
            ])->execute();

        }

        $sql = 'select c.*,m.full_name from "Clients" c
left join "Managers_clients" mc on c.id = mc.client_id 
join "Managers" m on m.id = mc.manager_id';
        $All_clients = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('index',['All_clients' => $All_clients]);
    }

    public function actionEdit()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $action = Yii::$app->request->post('action');

        $id = Yii::$app->request->get('id');

        $sql = 'select c.*,m.id as m_id,m.full_name,m.phone as phone_m,m.email as email_m,m.info
from "Clients" c
left join "Managers_clients" mc on c.id = mc.client_id 
join "Managers" m on m.id = mc.manager_id
WHERE c.id = '.$id;
        $Edit_client = Yii::$app->db->createCommand($sql)->queryOne();


        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $action = Yii::$app->request->post('action');

        if($action == 'save_client')
        {
            $name_company = Yii::$app->request->post('name_company');
            $director = Yii::$app->request->post('director_name');
            $manager = Yii::$app->request->post('manager_name');
            $phone_m = Yii::$app->request->post('phone_m');
            $email_m = Yii::$app->request->post('email_m');
            $phone = Yii::$app->request->post('phone');
            $email = Yii::$app->request->post('email');
            $info = Yii::$app->request->post('info');

            Yii::$app->db->createCommand()->update('Clients', [
                'company_name' => $name_company,
                'phone' => $phone,
                'email' => $email,
                'director_name' => $director
            ],'id='.$id)->execute();

            Yii::$app->db->createCommand()->update('Managers', [
                'full_name' => $manager,
                'phone' => $phone_m,
                'email' => $email_m,
                'info' => $info
            ],'id='.$Edit_client['m_id'])->execute();

        }

        $sql = 'select c.*,m.id as m_id,m.full_name,m.phone as phone_m,m.email as email_m,m.info
from "Clients" c
left join "Managers_clients" mc on c.id = mc.client_id 
join "Managers" m on m.id = mc.manager_id
WHERE c.id = '.$id;
        $Edit_client = Yii::$app->db->createCommand($sql)->queryOne();

        return $this->render('edit',['Edit_client' => $Edit_client]);
    }

}
