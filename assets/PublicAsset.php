<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;


class PublicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'public/css/bootstrap.min.css',
        'public/css/font-awesome.min.css',
        'public/css/animate.min.css',
        'public/css/owl.carousel.css',
        'public/css/owl.theme.css',
        'public/css/owl.transition.css',
        'public/css/style.css',
        'public/css/responsive.css',
    ];
    public $js = [
        'public/js/jquery-1.11.1.min.js',
        'public/js/bootstrap.min.js',
        'public/js/owl.carousel.min.js',
        'public/js/jquery.stickit.js',
        'public/js/menu.js',
        'public/js/scripts.js',
        'https://vk.com/js/api/share.js?93'
    ];

    public $depends = [

    ];
}
