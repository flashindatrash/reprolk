<?php
ini_set("display_errors", 1);

try {
	
	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application($config);
	
	$app->addRoute('Login', '/login');
	
	$app->connect();
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>