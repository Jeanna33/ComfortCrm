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

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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

    public function actionChangeLanguage($lang)
    {
        Yii::$app->language = $lang;
        Yii::$app->session->set('language', $lang);
        return $this->goBack(Yii::$app->request->referrer);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        return $this->render('dashboard');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()))
        {
            // Добавьте проверку
            Yii::debug('Attempt login for: ' . $model->username);
            if ($model->login())
            {
                return $this->goBack();
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionOrders()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }

        $sql = 'SELECT * FROM "Orders"  WHERE is_active = true ORDER BY "created_at"';
        $AllOrders = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = 'SELECT * FROM "Orders" WHERE is_active = false ORDER BY "created_at"';
        $EndOrders  = Yii::$app->db->createCommand($sql)->queryAll();

        $post_data = Yii::$app->request->post('action');

        if($post_data == 'save_order')
        {
            $text = Yii::$app->request->post('name_order');
            $info_about_order = Yii::$app->request->post('info_about_order');

            $uploadedFile = \yii\web\UploadedFile::getInstanceByName('file_order');
            $filePath = null;

            $uploadDir = Yii::getAlias('@webroot/uploads/');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $fileName = time() . '_' . $this->sanitizeFilename($uploadedFile->name);
            $filePath = $uploadDir . $fileName;

            if ($uploadedFile->saveAs($filePath)) {
                // Store relative path for database
                $filePath = '/uploads/' . $fileName;
            }
            else
            {
                Yii::$app->session->setFlash('error', 'Ошибка при загрузке файла');
                return $this->refresh();
            }

            Yii::$app->db->createCommand()->insert('Orders', [
                'name' => $text,
                'created_at' => date('Y-m-d H:i:s'),
                'info_about_order' => $info_about_order
            ])->execute();

            $id_order = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->insert('file', [
                'name' => $uploadedFile->name,
                'path_file' => $filePath
            ])->execute();

            $id_file_path = Yii::$app->db->getLastInsertID();

            Yii::$app->db->createCommand()->insert('file_order', [
                'file_id' => $id_file_path,
                'order_id' => $id_order
            ])->execute();

            Yii::$app->session->setFlash('success', 'Задача сохранена');

            $sql = 'SELECT * FROM "Orders" where is_active = true ORDER BY "created_at"';
            $AllOrders = Yii::$app->db->createCommand($sql)->queryAll();
        }


        return $this->render('orders', [
            'AllOrders' => $AllOrders,
            'EndOrders' => $EndOrders
        ]);
    }

    public function actionSaveOrders()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            return ['success' => false, 'message' => 'Только AJAX-запросы'];
        }
        $selectedOrders = Yii::$app->request->post('selected_orders');

        if (empty($selectedOrders)) {
            return ['success' => false, 'message' => 'Не выбрано ни одного заказа'];
        }

        // Преобразуем массив ID в строку для SQL (например: "1, 2, 3")
        $ids = implode(', ', array_map('intval', $selectedOrders));

        $sql = '
            UPDATE "Orders"
            SET is_active = false,
                updated_at = NOW() 
            WHERE id IN ('.$ids.')
        ';

        $updatedCount = Yii::$app->db->createCommand($sql)->execute();


        return [
            'success' => true,
            'message' => 'Обработано заказов: ' . count($selectedOrders),
        ];
    }

    public function actionSaveOrdersNow()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            return ['success' => false, 'message' => 'Только AJAX-запросы'];
        }
        $selectedOrders = Yii::$app->request->post('selected_orders');

        if (empty($selectedOrders)) {
            return ['success' => false, 'message' => 'Не выбрано ни одного заказа'];
        }

        // Преобразуем массив ID в строку для SQL (например: "1, 2, 3")
        $ids = implode(', ', array_map('intval', $selectedOrders));

        $sql = '
            UPDATE "Orders"
            SET is_active = true,
                updated_at = NOW() 
            WHERE id IN ('.$ids.')
        ';

        $updatedCount = Yii::$app->db->createCommand($sql)->execute();


        return [
            'success' => true,
            'message' => 'Обработано заказов: ' . count($selectedOrders),
        ];
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    private function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        return substr($filename, 0, 100); // limit filename length
    }
}
