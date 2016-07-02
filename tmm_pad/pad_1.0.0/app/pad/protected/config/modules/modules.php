<?php
/**
 * 模块配置文件
 */
return CMap::mergeArray(
    array(
        'admin'=>array(
            'class'=>'application.modules.admin.AdminModule',
            'ipFilters'=>array('*', '::1'),
            'defaultController'=>'index',
            'preload'=>array('log'),
            'controllerNamespace' => 'app\admin\controllers',
             'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info, error, warning',
                            'logFile'=> 'error/admin/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
             ),
        ),
        'user'=>array(
            'class'=>'application.modules.user.UserModule',
            'ipFilters'=>array('*', '::1'),
            'defaultController'=>'index',
            'preload'=>array('log'),
            'controllerNamespace' => 'app\user\controllers',
            'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info, error, warning',
                            'logFile'=> 'error/user/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
            ),
        ),
    ),
    file_exists(dirname(__FILE__).'/modules-dev.php') ? require(dirname(__FILE__).'/modules-dev.php') : array(),
    file_exists(dirname(__FILE__).'/modules-local.php') ? require(dirname(__FILE__).'/modules-local.php') : array()
);