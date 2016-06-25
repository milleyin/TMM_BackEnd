<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'田觅觅',
		
	'language'=>'zh_cn',
	'charset' => 'utf-8',
	'timeZone'=>'Asia/Shanghai',
		
	// preloading 'log' component
	'preload'=>array('log'),
				
	// autoloading model and component classes
	'import'=>array(
			'application.models.*',
			'application.components.*',
			'application.modules.srbac.controllers.SBaseController',
	),

	'commandMap' => array(
			//数据迁移
			'migrate' => array(
					'class'=>'application.commands.shell.MigrateCommand',
					'migrationTable'=>'{{migration}}',
			),
	),

	// application components
	'components'=>array(
			
		'cache'=>array(
				//'class'=>'CApcCache',
				//'class'=>'CMemCache',//缓存
				'class'=>'CFileCache',
		),
		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),
	
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					//'levels'=>'info,profile,error,warning',
					'levels'=>'info,error,warning',
					'logFile'=> 'error/console/' . date('Ymd') . '.log',
				),
// 				array(
// 						'class'=>'CEmailLogRoute',
// 						'levels'=>'error, warning',
// 						'emails'=>'761324952@qq.com',
// 				),
			),
		),

	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
