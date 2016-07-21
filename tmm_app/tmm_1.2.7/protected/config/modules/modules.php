<?php
// uncomment the following to enable the Gii tool
/**
 * 模块配置文件
 */
return CMap::mergeArray(
    array(
        /**
         * 管理员模块配置
         */
        'admin'=>array(
            'class'=>'application.modules.admin.AdminModule',
            'ipFilters'=>array('*','::1'),
            'defaultController'=>'tmm_home',
            'preload'=>array('log'),
            'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info,error,warning',
                            'logFile'=>'error/admin/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
            ),
        ),
        /**
         * 运营商 1.2.7 新建
         */
        'operator'=>array(
            'class'=>'application.modules.operator.OperatorModule',
            'ipFilters'=>array('*', '::1'),
            'defaultController'=>'home',
            'preload'=>array('log'),
            'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info,error,warning',
                            'logFile'=> 'error/operator/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
            ),
        ),
        /**
         * 商家
         */
        'store'=>array(
            'class'=>'application.modules.store.StoreModule',
            'ipFilters'=>array('*','::1'),
            'defaultController'=>'store_home',
            'preload'=>array('log'),
            'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info,error,warning',
                            'logFile'=> 'error/store/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
            ),
        ),
        /**
         * API
         */
        'api'=>array(
            'class'=>'application.modules.api.ApiModule',
            'ipFilters'=>array('*','::1'),
            'defaultController'=>'home',
            'preload'=>array('log'),
            'components'=>array(
                'log'=>array(
                    'class'=>'CLogRouter',
                    'routes'=>array(
                        array(
                            'class'=>'FileLogRoute',
                            'levels'=>'info,error,warning',
                            'logFile'=> 'error/api/' . date('Y-m-d') . '.log',
                        ),
                    ),
                ),
            ),
        ),
        //权限控制模块配置
        'srbac'=>array(
            'class'=>'application.modules.srbac.SrbacModule',
            'userclass' => 'Admin',//用户表模型 对该表用户做限制
            'userid' =>'id',
            'username' =>'username',
            'debug' => true,
            'delimeter'=>"@",
            'pageSize' => 20,
            'superUser' =>'权限管理角色',//超级权限的角色
            'css' => 'srbac.css',
            'layout' => 'admin.views.layouts.main_srbac',
            'notAuthorizedView' => 'admin.views.tmm_error.srbac',
            'alwaysAllowed'=>array(
            //    'StoreUserLogin',
            ),
            'userActions' => array(
            //    'Show', 'View', 'List'
            ),
            'listBoxNumberOfLines' => 30,
            'imagesPath' => 'srbac.images',
            'imagesPack' => 'tango',
            'iconText' => true,
            'cssPublished'=>true,
            'header' => 'srbac.views.authitem.header',
            'footer' => 'srbac.views.authitem.footer',
            'showHeader' => true,
            'showFooter' => false,
            'alwaysAllowedPath' => 'srbac.components',
        ),
    ),
    file_exists(dirname(__FILE__).'/modules-dev.php') ? require(dirname(__FILE__).'/modules-dev.php') : array(),
    file_exists(dirname(__FILE__).'/modules-local.php') ? require(dirname(__FILE__).'/modules-local.php') : array()
);