<?php
/**
 * 主要配置文件
 * 2016-7-19 ChangHai Zhan
 */
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    // app 名字
    'name' => '田觅觅',
    // 语言
    'language' => 'zh_cn',
    // 字符集
    'charset' => 'utf-8',
    // 时区
    'timeZone' => 'Asia/Shanghai',
    // 首页
    'homeUrl' => array('/'),
    // 预加载 组件 日志
    'preload' => array('log'),
    // 自动加载
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.srbac.controllers.SBaseController',
        'ext.rewrites.*',
    ),
    //模块配置
    'modules' => require(dirname(__FILE__) . '/modules/modules.php'),
    // 组件配置
    'components' => require(dirname(__FILE__) . '/components.php'),
    // 配置参数 Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
