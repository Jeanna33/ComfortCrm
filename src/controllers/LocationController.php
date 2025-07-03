<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Location;

class LocationController extends Controller
{
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $lat = Yii::$app->request->post('lat');
        $lng = Yii::$app->request->post('lng');

        if (Yii::$app->user->isGuest) {
            return ['status' => 'error', 'message' => 'Требуется авторизация'];
        }

        $location = new Location();
        $location->user_id = Yii::$app->user->id;
        $location->lat = $lat;
        $location->lng = $lng;
        $location->created_at = date('Y-m-d H:i:s'); // или можно использовать `new \yii\db\Expression('NOW()')`

        if ($location->save()) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'error',
            'errors' => $location->getErrors(),
        ];
    }
}
