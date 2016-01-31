<?php
ini_set("display_errors", 1);

try {
	
	define('PATH_PUBLIC_DIR', __DIR__);
	
	require_once ('../core/functions.php');
	
	$config = parse_ini_file('../app/config/config.ini', true);
	
	$app = new Application($config);
	
	$app->addLang('../app/config/lang/en.php');
	
	$app->setRoutes([
		new AccountRoute(Route::PROFILE, '/user', UserAccess::AUTH, Route::TYPE_SUB),
		new Route(Route::ORDER_ALL, '/order/all', UserAccess::ORDER_VIEW, Route::TYPE_NORMAL, [
			new Route(Route::ORDER_ADD, '/order/add', UserAccess::ORDER_ADD),
			new Route(Route::ORDER_ADD_TEMPLATE, '/order/add/template', UserAccess::ORDER_ADD),
			new Route(Route::ORDER_ARCHIVE, '/order/archive', UserAccess::ORDER_VIEW),
			new Route(Route::COMMENT_DELETE, '/order/comment/delete', UserAccess::COMMENT_EDIT, Route::TYPE_HIDDEN),
			new Route(Route::ORDER_VIEW, '/order/view', UserAccess::ORDER_VIEW, Route::TYPE_HIDDEN, [
				new Route(Route::ORDER_EDIT, '/order/edit', UserAccess::ORDER_EDIT, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_DUPLICATE, '/order/duplicate', UserAccess::ORDER_ADD, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_CANCEL, '/order/cancel', UserAccess::ORDER_EDIT, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_DELETE, '/order/delete', UserAccess::ORDER_DELETE, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_APPROVAL, '/order/approval', UserAccess::ORDER_EDIT, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_APPROVED, '/order/approve/success', UserAccess::ORDER_EDIT, Route::TYPE_HIDDEN),
				new Route(Route::ORDER_DISAPPROVED, '/order/approve/cancel', UserAccess::ORDER_EDIT, Route::TYPE_HIDDEN),
			]),
		]),
		
		new Route(Route::TEMPLATE_VIEW, '/template', UserAccess::TEMPLATE_VIEW, Route::TYPE_NORMAL, [
			new Route(Route::TEMPLATE_EDIT, '/template/edit', UserAccess::TEMPLATE_EDIT, Route::TYPE_HIDDEN),
			new Route(Route::TEMPLATE_DELETE, '/template/delete', UserAccess::TEMPLATE_EDIT, Route::TYPE_HIDDEN),
		]),
		
		new Route(Route::ADMIN, '/admin', UserAccess::MANAGEMENT, Route::TYPE_NORMAL, [
			new Route(Route::FIELD_PAGES, '/admin/fields', UserAccess::ADMIN, Route::TYPE_NORMAL, [
				new Route(Route::FIELD_PAGE, '/admin/fields/page', UserAccess::ADMIN, Route::TYPE_HIDDEN),
				new Route(Route::FIELD_DELETE, '/admin/fields/delete', UserAccess::ADMIN, Route::TYPE_HIDDEN),
				new Route(Route::FIELD_ADD, '/admin/fields/add', UserAccess::ADMIN, Route::TYPE_HIDDEN),
				new Route(Route::FIELD_BIND, '/admin/fields/bind', UserAccess::ADMIN, Route::TYPE_HIDDEN),
			]),
			new Route(Route::USER_ALL, '/admin/users', UserAccess::USER_VIEW, Route::TYPE_NORMAL, [
				new Route(Route::USER_ADD, '/admin/user-add', UserAccess::USER_ADD),
				new Route(Route::TRANSMIT_RIGHTS, '/admin/transmit-rights', UserAccess::TRANSMIT_RIGHTS),
				new Route(Route::VIEW_AS, '/admin/view-as', UserAccess::ADMIN, Route::TYPE_NORMAL, [
					new Route(Route::VIEW_AS_CANCEL, '/admin/view-as/cancel', UserAccess::ADMIN, Route::TYPE_HIDDEN),
				]),
			]),
			
			new Route(Route::POLYMER_ALL, '/admin/photopolymers', UserAccess::ADMIN, Route::TYPE_NORMAL, [
				new Route(Route::POLYMER_DELETE, '/admin/photopolymers/delete', UserAccess::ADMIN, Route::TYPE_HIDDEN),
				new Route(Route::POLYMER_BIND, '/admin/photopolymers/group', UserAccess::ADMIN, Route::TYPE_HIDDEN),
			]),
		]),
		
		new Route(Route::LOGIN, '/login', UserAccess::ALL, Route::TYPE_HIDDEN),
		new Route(Route::LOGOUT, '/logout', UserAccess::AUTH, Route::TYPE_SUB),
		
		new Route(Route::INDEX, '/', UserAccess::ALL, Route::TYPE_HIDDEN),
		new Route(Route::FILE, '/file', UserAccess::AUTH, Route::TYPE_HIDDEN),
		new Route(Route::NOT_FOUND, '/', UserAccess::ALL, Route::TYPE_HIDDEN),
		new Route(Route::ACCESS_DENIED, '/', UserAccess::ALL, Route::TYPE_HIDDEN),
	]);
	
	$app->connect();
	
	$app->getContent();

} catch (Exception $e) {

	echo $e->getMessage();
	
}

?>