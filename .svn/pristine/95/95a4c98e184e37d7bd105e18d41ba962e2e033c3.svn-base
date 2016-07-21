<?php
/**
 * 组件配置文件 
 * ChangHai Zhan
 * 2016-07-19
 */
return array(    
//     'session'=>array(
//             'class'=>'CHttpSession',
//     ),
//     'assetManager'=>array(
//             'class'=>'CAssetManager',
//     ),
//     'user'=>array(
//             'class'=>'CWebUser',
//     ),
//     'themeManager'=>array(
//             'class'=>'CThemeManager',
//     ),
//     'authManager'=>array(
//             'class'=>'CPhpAuthManager',
//     ),
//     'clientScript'=>array(
//             'class'=>'CClientScript',
//     ),
//     'widgetFactory'=>array(
//             'class'=>'CWidgetFactory',
//     ),

    'request'=>array(
        'class' => 'HttpRequest',
        'csrfTokenName' => 'TMM_CSRF',                      //令牌验证的名字
        'csrfCookie' => array(
            'httpOnly' => true,                                        //防止JS修改Cookie
            //'secure'=>true                                               //https
        ),
        'enableCookieValidation' => true,                     //全局 Cookie 是否加密
        'enableCsrfValidation' => true,                          //全局 post 验证
        'enableCrossValidation' => true,                       //全局 能否 跨域
        'crossDomainName' => '*',                                //全局 跨域 域名
        'enableHttpsValidation' => true,                       //全局 是否强制 https
        'enableRawBodyValidation' => true,                 //全局 是否开启 POST rawbody
    ),
    
    'format'=>array(
        'datetimeFormat'=>'Y/m/d H:i:s',                       //时间显示 Yii::app()->format->formatDatetime() 
        'dateFormat'=>'Y/m/d',                                      //时间显示 Yii::app()->format->formatDate()    
    ),
    
    'session'=> array(
        'class'=>'CHttpSession',
        'cookieParams'=>array(
            'httponly'=>true,                                           //防止JS修改Cookie
            //'secure'=>true                                               //https
        ),
        'cookieMode'=>'only',                                        //保存SessionID 仅仅用Cookie的方式保存
        'timeout' =>1440,
    ),
    
    'cookie'=>array(
        'class'=>'HttpCookie',
        'prefix'=>'cookie',
        'options'=>array(
            'httpOnly'=>true,                                             //防止JS修改Cookie
            //'secure'=>true                                                  //https
        ),
    ),
    
    'cache'=>array(
        //'class'=>'CApcCache',
        //'class'=>'CMemCache',//缓存
        'class'=>'CFileCache',
    ),
    
    /**
     * 缩略图 Yii::app()->thumb
     */
    'thumb'=>array(
        'class'=>'ext.CThumb.CThumb',
    ),
    
    'user'=>array(
        // enable cookie-based authentication
//         'class'=>'FrontWebUser',
//         'loginUrl'=>array('login/index'),
//         'loginRequiredAjaxResponse'=>array('login/index'),
//         'allowAutoLogin'=>true,
//         'autoRenewCookie'=>true,                            //自动登录的时候刷新Cooike的时间
//         'identityCookie'=>array(
//             'httpOnly'=>true,                                       //防止JS修改Cookie
//             //'secure'=>true                                           //https
//         ),
    ),
    
    /**
     * 权限表
     */
    'authManager' => array(
        'class' =>'srbac.components.SDbAuthManager',
        'connectionID' => 'db',
        'itemTable' => '{{zitems}}',
        'assignmentTable' => '{{zassignments}}',
        'itemChildTable' => '{{zitemchilds}}',
    ),
    
    //重写规则
    'urlManager'=>array(
        'class' => 'UrlManager',                                    //重写方法 兼容get
        'urlFormat'=>'path',
        'showScriptName' => false,                            //去除index.php
        'appendParams' => false,                               //参数不重写
        'urlSuffix'=>'.html',                                          //加上.html
        'rules'=>array(
            '<modules:\w+>/<controller:\w+>/<action:\w+>' => '<modules>/<controller>/<action>',
            '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
        ),
    ),

    // database settings are configured in database.php
    'db'=>require(dirname(__FILE__).'/database.php'),
    
    'errorHandler'=>array(
        'class'=>'CErrorHandler',
        'errorAction'=>YII_DEBUG ? null : 'site/error',
    ),
    
    'themeManager'=>array(
        'basePath'=>dirname(__FILE__) . '/../themes',
        //'baseUrl'=>,
    ),
    
    'log'=>array(
        'class'=>'CLogRouter',
        'routes'=>array(
            array(
                'class'=>'FileLogRoute',
                'levels'=>'error, warning, info',
                'logFile'=> 'error/' . date('Y-m-d') . '.log',
            ),
//             array(
//                 'class'=>'CWebLogRoute',
//                 'levels'=>'error, warning, trace',
//                 'categories'=>'system.db.*',
//             ),
        ),
    ),
);