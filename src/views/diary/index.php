<?php

use app\assets\AppAsset;

AppAsset::register($this);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Дневник</title>
</head>
<body>
<div class="container">
    <div class="row">
    </div>
    <div class="row">
        <div class="card col-md-12">
            <h5 class="card-title">Дневник</h5>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save_list">
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="text-diary" id="text-diary"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Настроение</label>
                        <input type="text" name="mood" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Добавить картинку</label>
                        <input type="file" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Сохранить запись">
                    </div>
                </form>
            </div>
        </div>
        <div class="card col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 20%">Название</th>
                    <th style="width: 35%">Текст</th>
                    <th style="width: 20%">Настроение</th>
                    <th style="width: 25%">Дата</th>
                </tr>
            <?php if(count($AllLists) > 0)
            {
                foreach ($AllLists as $item => $val)
                {
                    $file_in_data = '';
                    if(isset($val['file']['id']))
                    {
                        $file_in_data = '<img src="'.$val['file']['path_file'].'" alt='.$val['file']['name'].'>';
                    }
            ?>
                    <tr>
                        <td><?=$val['name']?></td>
                        <td><?=$val['text_list'].' '.$file_in_data?> </td>
                        <td><?=$val['mood']?></td>
                        <td><?=$val['created_at']?></td>
                    </tr>
            <?php
                }
            }
            ?>
            </table>
        </div>
    </div>
</div>
</body>
<script>

</script>