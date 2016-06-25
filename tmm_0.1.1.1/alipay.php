<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/alipay.php';
//错误定义
require_once(dirname(__FILE__).'/protected/extensions/status/error_code.php');
require_once(dirname(__FILE__).'/protected/extensions/status/error_code_msg.php');

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
//$config['defaultController']='Index/index';
require_once($yii);
Yii::createWebApplication($config)->run();
