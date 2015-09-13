<?php
ini_set("display_errors", 1);

try {
	
	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application($config);
	
	$app->addLang('../app/config/lang.php');
	
	$app->setRoutes([
		new Route('Profile', '/user', UserAccess::AUTH, false, [
			new Route('UserAdd', '/user/add', UserAccess::USER_ADD),
			new Route('UserAll', '/user/all', UserAccess::USER_ALL)
		]),
		new Route('OrderAll', '/order/all', UserAccess::AUTH, false, [
			new Route('OrderAdd', '/order/add', UserAccess::ORDER_ADD),
			new Route('OrderView', '/order/view', UserAccess::ORDER_ADD, true),
			new Route('OrderEdit', '/order/edit', UserAccess::ORDER_ADD, true)
		]),
		new Route('Logout', '/logout', UserAccess::AUTH),
		
		new Route('Login', '/login', UserAccess::ALL, true)
	]);
	
	$app->connect();
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>