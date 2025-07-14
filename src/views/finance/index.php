<?php

use app\assets\AppAsset;
use app\assets\StyleAsset;

AppAsset::register($this);
StyleAsset::register($this);
$this->title = 'Финансы';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?></title>
</head>
<body>


<div class="container">
    <div class="card col-md-6">
        <h5 class="card-title"><?= $this->title ?></h5>
        <div class="card-body">
            <h1>Cума</h1>
            <div id="graph"></div>
            <form method="post">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <label>Заработано на заказах (за всё время)</label>
                    <input type="number" id="finance" name="order_sum" value="<?=$sum['sum']?>" disabled class="form-control">
                </div>
                <div class="form-group">
                    <label>Заработано за этот день</label>
                    <input type="number" id="finance_sum_day" name="all_sum" class="form-control">
                </div>
                <div class="form-group">
                    <label>Сумма трат за этот день</label>
                    <input type="number" id="minus" name="minus" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" value="Cохранить" class="btn btn-success">
                </div>
            </form>

    </div>
</div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function () {
            $.ajax({
                url: '/finance/get-graph', // Укажите ваш URL обработчика
                type: 'POST',
                dataType: 'json',
                data: {
                    update_graph: '1'
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        let array_finance = response.message;

                        // Преобразуем данные в массив (если это объект)
                        const dataArray = Array.isArray(array_finance) ? array_finance : Object.values(array_finance);
                        const chartData = dataArray.map(item => {
                            const date = new Date(item.date);
                            const day = String(date.getDate()).padStart(2, '0'); // "09" вместо "9"
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // "07" вместо "7"
                            const xValue = `${day}.${month}.${date.getFullYear()}`; // "09.07.2025"
                            return {
                                x: xValue,
                                y: parseInt(item.sum),
                                z: parseInt(item.minus)
                            };
                        });

                        Morris.Bar({
                            element: 'graph',
                            data: chartData,
                            xkey: 'x',
                            ykeys: ['y', 'z'],
                            labels: ['Заработано', 'Потрачено']
                        }).on('click', function(i, row) {
                            console.log(i, row);
                        });
                    } else {
                        console.log(response);
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Произошла ошибка при отправке: ' + error);
                }
            });


        });
    });

</script>

