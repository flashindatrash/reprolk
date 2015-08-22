<?php
ini_set("display_errors", 1);

try {
	
	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application($config);
	
	$app->addLang('../app/config/lang.php');
	
	$app->addRoute(new Route('Account', '/user', UserAccess::USER_GET));
	$app->addRoute(new Route('UserAdd', '/user/add', UserAccess::USER_ADD));
	$app->addRoute(new Route('OrderAdd', '/order/add', UserAccess::ORDER_ADD));
	
	$app->connect();
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>