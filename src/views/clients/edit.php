<?php

use app\assets\AppAsset;

AppAsset::register($this);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование клиента</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="card col-md-12">
            <h5 class="card-title">Клиент <?=$Edit_client['company_name']?></h5>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                    <input type="hidden" name="action" value="save_client">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Название компании</label>
                                <input type="text" name="name_company" value="<?=$Edit_client['company_name']?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>ФИО владельца</label>
                                <input type="text" name="director_name" value="<?=$Edit_client['director_name']?>" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Номер телефона для связи</label>
                                <input type="text" name="phone" class="form-control" value="<?=$Edit_client['phone']?>">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" value="<?=$Edit_client['email']?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ФИО контактного лица</label>
                                <input type="text" name="manager_name" value="<?=$Edit_client['full_name']?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Номер телефона для связи c менеджером</label>
                                <input type="text" name="phone_m" class="form-control" value="<?=$Edit_client['phone_m']?>">
                            </div>
                            <div class="form-group">
                                <label>Email для связи с менеджером</label>
                                <input type="text" name="email_m" class="form-control" value="<?=$Edit_client['email_m']?>">
                            </div>
                            <div class="form-group">
                                <label>Для заметок</label>
                                <textarea name="info" class="form-control"><?=$Edit_client['info']?></textarea>
                            </div>
                        </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Сохранить">
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>