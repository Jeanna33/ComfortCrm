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

class DiaryController extends Controller
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

        if($action == 'save_list')
        {
            $uploadedFile = \yii\web\UploadedFile::getInstanceByName('image');
            $filePath = null;

            $uploadDir = Yii::getAlias('@webroot/uploads/');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if($uploadedFile?->name)
            {
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
            }

            $text_diary = Yii::$app->request->post('text-diary');
            $name = Yii::$app->request->post('name');
            $mood = Yii::$app->request->post('mood');

            Yii::$app->db->createCommand()->insert('diary_lists', [
                'name' => $name,
                'text_list' => $text_diary,
                'mood' => $mood
            ])->execute();

            $diary_list_id = Yii::$app->db->getLastInsertID();

            if($uploadedFile?->name)
            {
                Yii::$app->db->createCommand()->insert('file', [
                    'name' => $uploadedFile->name,
                    'path_file' => $filePath
                ])->execute();

                $id_file_path = Yii::$app->db->getLastInsertID();

                Yii::$app->db->createCommand()->insert('file_diary', [
                    'file_id' => $id_file_path,
                    'diary_list_id' => $diary_list_id
                ])->execute();
            }

        }

        $sql = 'SELECT * FROM "diary_lists" ORDER BY "created_at"';
        $rows = Yii::$app->db->createCommand($sql)->queryAll();

        $ids = [];
        foreach ($rows as $item => $val)
        {
            $ids[]=$val['id'];
        }

        $idsString = implode(',', $ids);

        $sql = 'SELECT * FROM "file_diary" fd
join file f on f.id  = fd.file_id
WHERE diary_list_id in ('.$idsString.')';
        $AllListsFiles = Yii::$app->db->createCommand($sql)->queryAll();

        $AllLists = [];
        foreach ($rows as $item => $val)
        {
            $AllLists[$val['id']] = $val;
            foreach ($AllListsFiles as $item_f => $val_f)
            {
                $AllLists[$val['id']]['file'] = [];
                if($val_f['diary_list_id'] == $val['id'])
                {
                    $AllLists[$val['id']]['file'] = $val_f;
                }
            }
        }

        return $this->render('index',[
            'AllLists' => $AllLists,
            'AllListsFiles' => $AllListsFiles
        ]);
    }

    private function sanitizeFilename($filename)
    {
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        return substr($filename, 0, 100); // limit filename length
    }
}