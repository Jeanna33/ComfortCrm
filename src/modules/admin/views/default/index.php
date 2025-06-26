<?php
$this->title = 'Панель администратора';
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#base-settings"
                type="button"
                role="tab"
                aria-selected="true"
        >Основные настройки</button>
    </li>
    <li class="nav-item">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#styles_in_crm"
                type="button"
                role="tab"
                aria-selected="false"
        >Настройка стилей</button>
    </li>
    <li class="nav-item" >
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#addons"
                type="button"
                role="tab"
                aria-selected="false"
        >Дополнения</button>
    </li>
    <li class="nav-item" >
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#addons"
                type="button"
                role="tab"
                aria-selected="false"
        >Настройка подключения к хабам</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="base-settings">123</div>
    <div class="tab-pane fade" id="styles_in_crm">345</div>
    <div class="tab-pane fade" id="addons">567</div>
    <div class="tab-pane fade" id="habs">567</div>
</div>