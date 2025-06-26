<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css', // Добавьте Bootstrap CSS
        'css/tailwind.min.css',
        'css/site.css',
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',        // Подключает jQuery
        'yii\bootstrap5\BootstrapAsset',   // Если Bootstrap 5
        'yii\bootstrap5\BootstrapPluginAsset', // JS для Bootstrap 5
    ];
}