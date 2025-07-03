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
                        <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th>ID задачи</th>
                                <th>Название</th>
                                <th>Дата окончания</th>
                                <th>Сумма</th>
                                <th></th>
                            </tr>
                            </thead>
                            <?php
                            foreach ($EndOrders as $item => $val)
                            {
                                ?>
                                <tr id="order_<?=$val['id']?>" class="table-row-clickable">
                                    <td><?=$val['id']?></td>
                                    <td><?=$val['name']?></td>
                                    <td><?=$val['date_end']?></td>
                                    <td><?=$val['sum']?> <?=$val['currency']?></td>
                                    <td><input type="checkbox" value="1" name="orders_now[<?=$val['id']?>]"></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                       <input type="button" class="btn btn-primary" value="Вернуть в работу" id="send_in_work">
                       <input type="button" class="btn btn-warning" value="Удалить" id="delete_order">

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
                    <table class="table table-bordered " >
                        <thead>
                        <tr>
                            <th>ID задачи</th>
                            <th>Название</th>
                            <th>Дата добавления</th>
                            <th>Сумма</th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php
                        foreach ($AllOrders as $item => $val)
                        {
                            ?>
                            <tr id="order_<?=$val['id']?>" class="table-row-clickable">
                                <td><?=$val['id']?></td>
                                <td><?=$val['name']?></td>
                                <td><?=$val['created_at']?></td>
                                <td><?=$val['sum']?> <?=$val['currency']?></td>
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
                            <textarea name="info_about_order" class="form-control" id="info_about_order_new"></textarea>
                        </div>
                        <div class="form-group">
                            Сумма <input type="number" name="sum_order" class="form-control" required>
                        </div>
                        <div class="form-group">
                            Валюта
                            <select class="form-control" name="currency" required>
                                <option value="rub">Рубль</option>
                                <option value="usd">Доллар</option>
                            </select>
                        </div>
                        <label>Приложить файл</label>
                        <div class="form-group">
                            <input type="file" name="file_order" class="form-control" id="file_order">
                        </div>
                        <div class="form-group">
                            <label>Выберите компанию/ИП</label>
                            <select class="form-control" name="client">
                                <option value="0"></option>
                                <?php foreach ($Clients as $item => $val) { ?>
                                    <option value="<?=$val['id']?>"><?=$val['company_name']?></option>
                                <?php }?>
                            </select>
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


<div class="modal fade" id="ModalInfo" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelInfo">Информация о задаче</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mt-4">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                        <input type="hidden" name="action" value="update_order">
                        <input type="hidden" name="order_id" id="order_id">
                        <label>Название задачи</label>
                        <div class="form-group">
                            <input type="text" name="name_order" class="form-control" id="name_order_info">
                        </div>
                        <label>Описание</label>
                        <div class="form-group">
                            <textarea class="form-control" id="info_about_order_info" name="info_about_order_info"></textarea>
                        </div>
                        <div class="form-group">
                            Сумма <input type="number" name="sum_order" class="form-control" id="sum_order_info" required>
                        </div>
                        <div class="form-group">
                            Валюта
                            <select class="form-control" name="currency" required id="currency_sum">
                                <option value="rub">Рубль</option>
                                <option value="usd">Доллар</option>
                            </select>
                        </div>
                        <label>Файл</label>
                        <div class="form-group" id="link_container">
                            <label>Приложить файл</label>
                            <div class="form-group">
                                <input type="file" name="file_order" class="form-control" id="file_order">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Выберите компанию/ИП</label>
                            <select class="form-control" name="client" id="client_company">
                                <option value="0"></option>
                                <?php foreach ($Clients as $item => $val) { ?>
                                    <option value="<?=$val['id']?>"><?=$val['company_name']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Сохранить изменения" class="btn btn-success" id="save_order_changes">
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


            $('#send_in_work').click(function() {

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

            $('#delete_order').click(function() {
                if (confirm('Вы уверены, что хотите продолжить?')) {
                    // Пользователь нажал "ОК"
                    alert('Действие выполнено!');
                    let info = getSelectedOrdersNow();
                    $.ajax({
                        url: '/order/delete-order', // Укажите ваш URL обработчика
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
                            console.log(xhr,status,error);
                            alert('Произошла ошибка при отправке: ' + error);
                        }
                    });
                } else {
                    // Пользователь нажал "Отмена"
                    alert('Действие отменено.');
                }

            });

            $('.table-row-clickable td:not(:last-child)').on('click', function() {
                const row = $(this).closest('tr');
                const orderId = row.attr('id');
                const number = parseInt(orderId.split('_')[1]);

                // Запрашиваем данные о заказе
                $.ajax({
                    url: '/order/get-info', // Укажите ваш URL для получения информации о заказе
                    type: 'POST',
                    dataType: 'json',
                    data: { order_id: number },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            // Заполняем модальное окно данными
                            $('#ModalLabelInfo').text('Информация о задаче #' + response.data.id);
                            $('#order_id').val(response.data.id);
                            $('#name_order_info').val(response.data.name);
                            $('#info_about_order_info').val(response.data.info_about_order);
                            $('#sum_order_info').val(response.data.sum);
                            $('#currency_sum').val(response.data.currency);
                            $('#client_company').val(response.data_client.id);
                            console.log(response);

                            // Отображаем файл, если он есть
                            if (response.files && response.files.length > 0)
                            {
                                let linksHtml = '';
                                response.files.forEach(file => {
                                    linksHtml += `<a href="${file.path_file}" target="_blank" id="link-path-${file.id}">${file.name}</a><input type="button" value="X" class="btn delete_file" id="delete_${file.id}"><br>`;
                                });
                                linksHtml += '<br/><input type="file" name="file_order" class="form-control" id="file_order" value="Приложить файл">';
                                $('#link_container').html(linksHtml);
                            }
                            else
                            {
                                $('#link_container').html('<input type="file" name="file_order" class="form-control" id="file_order">');
                            }

                            // Показываем модальное окно
                            const modal = new bootstrap.Modal(document.getElementById('ModalInfo'));
                            modal.show();
                        } else {
                            alert('Ошибка: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Произошла ошибка при получении данных: ' + error);
                    }
                });
            });


            $(document).on("click", ".delete_file", function() {
                const orderId = $(this).attr('id');
                const number = parseInt(orderId.split('_')[1]);
                console.log(orderId,number);
                $.ajax({
                    url: '/order/delete-file', // Укажите ваш URL для получения информации о заказе
                    type: 'POST',
                    dataType: 'json',
                    data: {id_file: number},
                    success: function (response) {
                        if (response.success) {
                            $('#link-path-'+number).remove();
                            $('#delete_'+number).remove();
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Произошла ошибка при получении данных: ' + error);
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