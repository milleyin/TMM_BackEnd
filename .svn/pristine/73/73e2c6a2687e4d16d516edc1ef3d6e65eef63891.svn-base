<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
if ( !YII_DEBUG) {
    !YII_DEBUG && error_reporting(0);
    $config = dirname(__FILE__).'/../protected/config/main.php';
} else {
    error_reporting(E_ALL);
    $config = dirname(__FILE__).'/../protected/config/test.php';
}
//引入Yii框架
require_once(dirname(__FILE__).'/../../../framework/yii.php');
Yii::createWebApplication($config)->run();