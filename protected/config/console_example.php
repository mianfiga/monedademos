<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'đ DEMOS: Democracia Económica con MOneda Social',
	// application components
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	'components'=>array(
		'db'=>array(
	            'connectionString' => 'mysql:host=localhost;dbname=rbu',
	            'emulatePrepare' => true,
	            'username' => 'root',
	            'password' => 'root',
	            'charset' => 'utf8',
	            'tablePrefix' => 'rbu_',
	        ),
		'request' => array(//MODIFY THIS TO MAKE NOTIFICATIONS WORK
	            'hostInfo' => 'http://monedademos.es',
	            'baseUrl' => 'BASE_URL',
	            'scriptUrl' => '',
	        ),
	),
);
