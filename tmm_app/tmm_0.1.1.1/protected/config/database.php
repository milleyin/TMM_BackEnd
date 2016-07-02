<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database
	/*
	'connectionString' => 'mysql:host=localhost;dbname=testdrive',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	*/
	'connectionString' => 'mysql:host=localhost;dbname=tianmimi;charset=utf8',
	//'connectionString' => 'mysql:host=172.16.1.158;dbname=0929;charset=utf8',
	'emulatePrepare' => true,
	//'username' => 'root',
	'username'=>'root',
	//'password' => 'root',
	'password'=>'',
	'charset' => 'utf8',
	'tablePrefix' =>'tmm_',
	'schemaCachingDuration'=>3600*24*7,
	'enableProfiling'=>YII_DEBUG,
	'enableParamLogging'=>YII_DEBUG,
);