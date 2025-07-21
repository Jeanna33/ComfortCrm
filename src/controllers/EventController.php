<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Event;

class EventController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Получение событий в формате JSON для FullCalendar
     */
    public function actionList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $events = Event::find()->all();
        $result = [];

        foreach ($events as $event) {
            $result[] = $event->toFullCalendarEvent();
        }

        return $result;
    }

    /**
     * Создание нового события
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Event();
        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            return ['success' => true, 'id' => $model->id];
        }

        return ['success' => false, 'errors' => $model->errors];
    }

    /**
     * Обновление существующего события
     */
    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = Event::findOne($id);

        if (!$model) {
            return ['success' => false, 'message' => 'Событие не найдено'];
        }

        $model->load(Yii::$app->request->post(), '');

        if ($model->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'errors' => $model->errors];
    }

    /**
     * Удаление события
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $model = Event::findOne($id);

        if (!$model) {
            return ['success' => false, 'message' => 'Событие не найдено'];
        }

        if ($model->delete()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Ошибка при удалении'];
    }

    /**
     * Обработка изменений через Drag-and-Drop в календаре
     */
    public function actionMove($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Event::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Событие не найдено');
        }

        $model->start_event = Yii::$app->request->post('start');
        $model->end_event = Yii::$app->request->post('end');
        $model->all_day = Yii::$app->request->post('allDay', false);

        if ($model->save()) {
            return ['status' => 'success'];
        }

        return ['status' => 'error', 'errors' => $model->errors];
    }
}