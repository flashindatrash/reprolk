<?php

try {

	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application();
	
	$app->url = $_GET['_url'];
	
	$app->addResource('Index', '/');
	$app->addResource('Login', '/login');
	
	//$app->connect($config['database']);
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>