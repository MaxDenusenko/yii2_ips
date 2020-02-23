<?php

namespace frontend\assets;

use macgyer\yii2materializecss\assets\MaterializeFontAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        'css/materialize.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/materialize.min.js',
        'js/site.js',
    ];
    public $depends = [
        MaterializeFontAsset::class,
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'macgyer\yii2materializecss\assets\MaterializeAsset',
    ];
}
