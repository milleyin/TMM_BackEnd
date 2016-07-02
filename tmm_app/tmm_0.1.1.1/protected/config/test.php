<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			/* uncomment the following to provide test database connection
			'db'=>array(
				'connectionString'=>'DSN for test database',
			),
			*/
			'log'=>array(
					'class'=>'CLogRouter',
					'routes'=>array(
							array(
									'class'=>'CFileLogRoute',
									'levels'=>'error, warning',
									'logFile'=> 'error/test/' . date('Ymd') . '.log',
							),
							array(
									'class'=>'CWebLogRoute',
									'levels'=>'error, warning,trace',
									'categories'=>'system.db.*',
							),
					),
			),
		),
	)
);
