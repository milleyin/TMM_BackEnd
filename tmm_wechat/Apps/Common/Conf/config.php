<?php
return array(
	//'配置项'=>'配置值'

    /* URL设置 */
    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'DEFAULT_MODULE'        =>  'Sakura',  // 默认模块
    /* 数据库配置 */
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'tmm_wx', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '',  // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'wx_', // 数据库表前

    /* 数据缓存设置 */
    'DATA_CACHE_TIME' => 86400, // 数据缓存有效期 0表示永久缓存

    /* 日志设置 */
    'LOG_RECORD'            =>  true,   // 默认不记录日志
    'LOG_EXCEPTION_RECORD'  =>  true,    // 是否记录异常信息日志


    /*短信接口配置*/
    'SMS' => array(
        'sms_key'=>'f1a763ef51b3828d7acadda7d5353a69',
        'sms_url'=>'http://sms-api.luosimao.com/v1/send.json',
    ),
);