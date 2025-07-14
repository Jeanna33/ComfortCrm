<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class LocationController extends Controller
{
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        if (!isset($data['latitude'], $data['longitude'], $data['user_id'])) {
            return ['success' => false, 'message' => 'Недостаточно данных'];
        }

        $location = new Location();
        $location->user_id = (int)$data['user_id'];
        $location->latitude = (float)$data['latitude'];
        $location->longitude = (float)$data['longitude'];
        $location->created_at = time();

        if ($location->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => $location->getErrors()];
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

    // Экшен для страницы с картой
    public function actionMap()
    {
        return $this->render('map'); // Рендерит views/location/map.php
    }
}
