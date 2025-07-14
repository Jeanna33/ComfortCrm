<?php

namespace app\assets;

use yii\web\AssetBundle;

class StyleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/morris.css',
        'css/bootstrap-datepicker.min.css'
    ];
    public $js = [
        'js/raphael.min.js',
        'js/morris.js',
        'js/bootstrap-datepicker.min.js',
        'js/bootstrap-datepicker.ru.min.js'
    ];
}