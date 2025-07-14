<?php

use app\assets\AppAsset;
use app\assets\StyleAsset;

AppAsset::register($this);
StyleAsset::register($this);
$this->title = 'Календарь';
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
            <div class="input-group" id="datecheck"></div>
        </div>
    </div>
</div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function () {
            $('#datecheck').datepicker({
                language: 'ru',
                format: 'dd.mm.yyyy',
                todayHighlight: true,
                weekStart: 1
            });
        })
    });
</script>

