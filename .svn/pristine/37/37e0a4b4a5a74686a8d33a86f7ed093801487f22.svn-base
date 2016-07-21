<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'fixture'=>array(
                'class'=>'system.test.CDbFixtureManager',
            ),
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'FileLogRoute',
                        'levels'=>'error, warning',
                        'logFile'=> 'error/test/' . date('Y-m-d') . '.log',
                    ),
                    array(
                        'class'=>'CWebLogRoute',
                        'levels'=>'',
                        'categories'=>'',
                        //'showInFireBug' => true,
                    ),
                ),
            ),
        ),
    )
);
