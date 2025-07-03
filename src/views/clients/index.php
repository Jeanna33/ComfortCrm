<?php

use app\assets\AppAsset;

AppAsset::register($this);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клиенты</title>
</head>
<body>
<div class="container">
    <div class="row">
        <form method="post">
            <input type="hidden" name="action" value="save_client">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <div class="form-group">
                <label>Название компании</label>
                <input type="text" name="name_company" class="form-control" required>
            </div>
            <div class="form-group">
                <label>ФИО владельца</label>
                <input type="text" name="director_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>ФИО контактного лица</label>
                <input type="text" name="manager_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Номер телефона для связи</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Сохранить">
            </div>
        </form>
    </div>
    <div class="row">
        <div class="card col-md-12">
            <h5 class="card-title">Клиенты</h5>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Название компании/клиента</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>ФИО владельца</th>
                        <th>ФИО контактного лица</th>
                    </tr>
                    <?php foreach ($All_clients as $item => $val) { ?>
                        <tr>
                            <td><?=$val['company_name']?> <a href="/clients/edit?id=<?=$val['id']?>" class="btn btn-success">⚙️</a> </td>
                            <td><?=$val['phone']?></td>
                            <td><?=$val['email']?></td>
                            <td><?=$val['director_name']?></td>
                            <td><?=$val['full_name']?></td>
                        </tr>

                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

</div>