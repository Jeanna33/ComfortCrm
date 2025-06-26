<?php

use app\assets\AppAsset;

AppAsset::register($this);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы</title>
</head>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вертикальная прокрутка карточек</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            height: 600px; /* Фиксированная высота карточки */
        }
        .card-body {
            height: 100%; /* Заполняет всю высоту карточки */
            overflow-y: auto; /* Включает вертикальную прокрутку */
            padding: 15px;
        }
        .card-body::-webkit-scrollbar {
            width: 8px;
        }
        .card-body::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }
        .card-body::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3 pb-4">
            <input type="button" class="btn btn-primary" value="Добавить задачу" id="append_order">
        </div>
    </div>
    <div class="row">
        <div class="card col-md-6">
            <div class="card-body">
                <h5 class="card-title">Отработаны</h5>
                    <?php if(count($EndOrders) > 0)
                    {
                        ?>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID задачи</th>
                                <th>Название</th>
                                <th>Дата окончания</th>
                                <th></th>
                            </tr>
                            </thead>
                            <?php
                            foreach ($EndOrders as $item => $val)
                            {
                                ?>
                                <tr>
                                    <td><?=$val['id']?></td>
                                    <td><?=$val['name']?></td>
                                    <td><?=$val['date_end']?></td>
                                    <td><input type="checkbox" value="1" name="orders_now[<?=$val['id']?>]"></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                       <input type="button" class="btn btn-primary" value="Вернуть в работу" id="send_in_work">

                    <?php
                    }
                    ?>

            </div>
        </div>
        <div class="card col-md-6">
            <div class="card-body">
                <h5 class="card-title">В работе</h5>
                <?php if(count($AllOrders) > 0)
                {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID задачи</th>
                            <th>Название</th>
                            <th>Дата добавления</th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php
                        foreach ($AllOrders as $item => $val)
                        {
                            ?>
                            <tr>
                                <td><?=$val['id']?></td>
                                <td><?=$val['name']?></td>
                                <td><?=$val['created_at']?></td>
                                <td><input type="checkbox" value="1" name="orders[<?=$val['id']?>]"></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <div class="form-group">
                        <input type="button" class="btn btn-primary" value="Перенести в отработанные" id="send_end">
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Добавление задачи</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mt-4">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" name="action" value="save_order">
                        <label>Название задачи</label>
                        <div class="form-group">
                            <input type="text" name="name_order" class="form-control" id="name_order">
                        </div>
                        <label>Описание</label>
                        <div class="form-group">
                            <textarea name="info_about_order" class="form-control" id="info_about_order"></textarea>
                        </div>
                        <label>Приложить файл</label>
                        <div class="form-group">
                            <input type="file" name="file_order" class="form-control" id="file_order">
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Сохранить" class="btn btn-success">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
     document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {


            $("#append_order").on("click", function() {
                const modal = new bootstrap.Modal(document.getElementById('Modal'));
                modal.show();
            });


            $('#send_in_work').click(function() {

                function getSelectedOrdersNow() {
                    let selectedOrders = [];

                    // Перебираем все отмеченные чекбоксы с name="orders[...]"
                    $('input[name^="orders_now"]:checked').each(function() {
                        // Извлекаем ID из имени (orders[ID])
                        let orderId = $(this).attr('name').match(/\[(.*?)\]/)[1];
                        selectedOrders.push(orderId);
                    });

                    return selectedOrders;
                }
                let info = getSelectedOrdersNow();

                $.ajax({
                    url: '/site/save-orders-now', // Укажите ваш URL обработчика
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        selected_orders: info
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            // Можно обновить страницу или выполнить другие действия
                            location.reload();
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


            $('#send_end').click(function() {
                function getSelectedOrders() {
                    let selectedOrders = [];

                    // Перебираем все отмеченные чекбоксы с name="orders[...]"
                    $('input[name^="orders"]:checked').each(function() {
                        // Извлекаем ID из имени (orders[ID])
                        let orderId = $(this).attr('name').match(/\[(.*?)\]/)[1];
                        selectedOrders.push(orderId);
                    });

                    return selectedOrders;
                }
                let info = getSelectedOrders();

                $.ajax({
                    url: '/site/save-orders', // Укажите ваш URL обработчика
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        selected_orders: info
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            // Можно обновить страницу или выполнить другие действия
                             location.reload();
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

    });
</script>