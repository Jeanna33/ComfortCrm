<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class AdminController extends Controller
{
    public function actionCreate($token)
    {
        // Проверка токена из .env
        $validToken = getenv('ADMIN_CREATION_TOKEN');
        if ($token !== $validToken) {
            $this->stdout("Ошибка: Неверный токен!\n", Console::FG_RED);
            return Controller::EXIT_CODE_ERROR;
        }

        // Проверка существования администратора
        $auth = Yii::$app->authManager;
        if ($auth->getAssignment('admin', 1)) {
            $this->stdout("Администратор уже существует!\n", Console::FG_YELLOW);
            return Controller::EXIT_CODE_NORMAL;
        }

        // Создание пользователя
        $user = new \app\models\User();
        $user->username = 'admin';
        $user->email = 'admin@example.com';
        $user->setPassword(getenv('ADMIN_DEFAULT_PASSWORD'));
        $user->generateAuthKey();
        $user->status = 10; // Активированный аккаунт

        if ($user->save()) {
            // Создание роли 'admin' (если её нет)
            $adminRole = $auth->getRole('admin');
            if (!$adminRole) {
                $adminRole = $auth->createRole('admin');
                $auth->add($adminRole);
            }

            // Назначение роли
            $auth->assign($adminRole, $user->id);
            $this->stdout("Администратор создан! ID: {$user->id}\n", Console::FG_GREEN);
        } else {
            $this->stdout("Ошибка: " . print_r($user->errors, true) . "\n", Console::FG_RED);
        }
    }
}