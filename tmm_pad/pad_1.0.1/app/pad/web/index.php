<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
// change the following paths if necessary
$yii = dirname(__FILE__).'/../../../framework/yii.php';
$config = dirname(__FILE__).'/../protected/config/main.php';

!YII_DEBUG && error_reporting(0);
require_once($yii);
Yii::createWebApplication($config)->run();