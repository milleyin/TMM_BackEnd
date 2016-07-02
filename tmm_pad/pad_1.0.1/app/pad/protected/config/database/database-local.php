<?php

// This is the database connection configuration.
return array(
    'connectionString' => 'mysql:host=localhost;dbname=app_pad;charset=utf8',
    'emulatePrepare' => true,
    'username'=>'root',
    'password'=>'',
    'charset' => 'utf8',
    'tablePrefix' =>'pad_',
    'schemaCachingDuration'=>3600*24*7,
    'enableProfiling'=>YII_DEBUG,
    'enableParamLogging'=>YII_DEBUG,
);