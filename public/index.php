<?php
ini_set("display_errors", 1);

try {
	
	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application($config);
	
	$app->addLang('../app/config/lang.php');
	
	$app->setRoutes([
		new Route('User', '/user', UserAccess::AUTH, false, [
			new Route('UserAdd', '/user/add', UserAccess::USER_ADD),
			new Route('UserAll', '/user/all', UserAccess::USER_VIEW)
		]),
		new Route('OrderAll', '/order/all', UserAccess::ORDER_VIEW, false, [
			new Route('OrderAdd', '/order/add', UserAccess::ORDER_ADD),
			new Route('OrderView', '/order/view', UserAccess::ORDER_VIEW, true),
			new Route('OrderEdit', '/order/edit', UserAccess::ORDER_ADD, true),
			new Route('OrderDuplicate', '/order/duplicate', UserAccess::ORDER_ADD, true),
		]),
		
		new Route('Admin', '/admin', UserAccess::ADMIN, false, [
			new Route('ViewAs', '/admin/view-as', UserAccess::ADMIN, false, [
				new Route('ViewAsCancel', '/admin/view-as/cancel', UserAccess::ADMIN, true),
			]),
			new Route('Photopolymers', '/admin/photopolymers', UserAccess::ADMIN, false, [
				new Route('GroupPhotopolymers', '/admin/photopolymers/group', UserAccess::ADMIN),
			]),
		]),
		
		new Route(Route::LOGIN, '/login', UserAccess::ALL, true),
		new Route(Route::LOGOUT, '/logout', UserAccess::AUTH),
		
		new Route(Route::INDEX, '/', UserAccess::ALL, true),
		new Route(Route::NOT_FOUND, '/', UserAccess::ALL, true),
		new Route(Route::ACCESS_DENIED, '/', UserAccess::ALL, true),
	]);
	
	$app->connect();
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>