<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'田觅觅',

	'language'=>'zh_cn',
	'charset' => 'utf-8',
	'timeZone'=>'Asia/Shanghai',
	'homeUrl'=>array('/'),
		
	//'defaultController'=>'Index/index',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.srbac.controllers.SBaseController',
	),
	
	'modules'=>array(
		// uncomment the following to enable the Gii tool
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
												'class'=>'CFileLogRoute',
												'levels'=>'info,error,warning',
												'logFile'=> 'error/admin/' . date('Ymd') . '.log',
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
				'ipFilters'=>array('*','::1'),
				'defaultController'=>'home',
				'preload'=>array('log'),
				'components'=>array(
						'log'=>array(
								'class'=>'CLogRouter',
								'routes'=>array(
										array(
												'class'=>'CFileLogRoute',
												'levels'=>'info,error,warning',
												'logFile'=> 'error/operator/' . date('Ymd') . '.log',
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
 												'class'=>'CFileLogRoute',
 												'levels'=>'info,error,warning',
 												'logFile'=> 'error/store/' . date('Ymd') . '.log',
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
												'class'=>'CFileLogRoute',
												'levels'=>'info,error,warning',
												'logFile'=> 'error/api/' . date('Ymd') . '.log',
										),
								),
						),
				),
		),
		/*
		'wechat'=>array(
			'class'=>'application.modules.wechat.WechatModule',
			'ipFilters'=>array('*','::1'),
			'defaultController'=>'wx_home',
		),*/
		//代码生成工具	
		'gii'=>array(
			'class'=>'application.modules.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
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
						//	'StoreUserLogin',
				),
				'userActions' => array(
						//	'Show', 'View', 'List'
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

	// application components
	'components'=>array(

		//默认模块设置
// 		'user'=>array(
// 				// enable cookie-based authentication
// 				'allowAutoLogin'=>true,
// 				'stateKeyPrefix'=>'member',
// 				'loginRequiredAjaxResponse'=>'YII_LOGIN_REQUIRED',
// 				'loginUrl'=>array('login'),
// 		),
		
		'request'=>array(
				'class'=>'HttpRequest',
				'csrfTokenName'=>'TMM_CSRF',		//令牌验证的名字
				'csrfCookie'=>array('httpOnly'=>true),//防止JS修改Cookie
				'enableCookieValidation'=>true, 	//Cookie
				'enableCsrfValidation'=>true,  		//post 验证
		),
		
		'format'=>array(
				'datetimeFormat'=>'Y/m/d H:i:s',  //时间显示 Yii::app()->format->formatDate()
				'timeFormat'=>'Y/m/d',					//时间显示 Yii::app()->format->formatDatetime()
		),
		
		'session' => array(
				'class'=>'CHttpSession',
				'cookieParams'=>array('httponly'=>true),//防止JS修改Cookie
				'cookieMode'=>'only',//保存SessionID 仅仅用Cookie的方式保存
			//	'timeout' =>1440,
		),
		
		'cookie'=>array(
				'class'=>'HttpCookie',
				'prefix'=>'cookie',
				'options'=>array(
					'httpOnly'=>true,//防止JS修改Cookie
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
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		
// 		'urlManager'=>array(
// 			'urlFormat'=>'path',
// 			'showScriptName'=>false,//注意false不要用引号括上
// 			'urlSuffix'=>'.html',				//搭车加上.html后缀，霸道
//  			'rules'=>array(
//  				'<modules:(admin|srbac|api)>/<controller:\w+>/<action:\w+>/<name:\w+>/<id:\d+>'
//  					=>'<modules>/<controller>/<action>/<name>/<id>',		
//  				'<controller:\w+>'=>'<controller>',
//  				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
//  				'<controller:\w+>/<action:\w+>/<name:\w+>/<id:\d+>'=>'<controller>/<action>/<name:\w+>/<id:\d+>',
// 			),
// 		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info,error,warning',
					'logFile'=> 'error/' . date('Ymd') . '.log',
				),
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
