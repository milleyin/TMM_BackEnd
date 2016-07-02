<?php

// This is the database connection configuration.
return CMap::mergeArray(
    array(
//         'connectionString' => 'mysql:host=10.46.68.198;dbname=app_pad;charset=utf8',
//         'emulatePrepare' => true,
//         'username'=>'app_pad',
//         'password'=>'XdLtjU3AuqafdDGt',
//         'charset' => 'utf8',
//         'tablePrefix' =>'pad_',
//         'schemaCachingDuration'=>3600*24*7,
//         'enableProfiling'=>YII_DEBUG,
//         'enableParamLogging'=>YII_DEBUG,
    ),
    file_exists(dirname(__FILE__).'/database-dev.php') ? require(dirname(__FILE__).'/database-dev.php') : array(),
    file_exists(dirname(__FILE__).'/database-local.php') ? require(dirname(__FILE__).'/database-local.php') : array()
);