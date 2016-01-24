<?php

class UserAccess extends BaseModel {
	
	const NONE = 'none';
	const ALL = 'all';
	const AUTH = 'auth';
	
	const ADMIN = 'admin';
	const MANAGEMENT = 'management'; //доступность меню администрирования
	const ORDER_VIEW = 'orderView';
	const ORDER_ADD = 'orderAdd';
	const ORDER_EDIT = 'orderEdit'; //кто может вносить правки в заказ
	const ORDER_DELETE = 'orderDelete'; //кто может удалять заказ
	const USER_VIEW = 'userView'; //кто может просматривать список пользователей
	const USER_ADD = 'userAdd'; //кто может добавлять новых пользователей
	const USER_ADD_ADMIN = 'auth'; //кого может добавлять админ
	const USER_ADD_MANAGER = 'userAddManager'; //кого может добавлять манагер
	const TRANSMIT_RIGHTS = 'transmitRights'; //передавать права
	const COMMENT_ADD = 'commentAdd'; //добавлять комментарии
	const COMMENT_EDIT = 'commentEdit'; //редактировать комментарии
	const TEMPLATE_VIEW = 'templateView'; //просматривать темплейты
	const TEMPLATE_EDIT = 'templateEdit'; //устанавливать темплейты
	
	public static $permissions = array (
		'none' => [],
		'all' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER, User::ANONYMOUS],
		'auth' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER],
		'orderAdd' => [/*debug*/User::ADMIN, User::MANAGER, User::CLIENT],
		'orderEdit' => [/*debug*/User::ADMIN, User::MANAGER, User::CLIENT],
		'orderView' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER],
		'orderDelete' => [User::ADMIN],
		'commentAdd' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER],
		'commentEdit' => [User::ADMIN, User::MANAGER, User::CLIENT],
		'userAdd' => [User::ADMIN, User::MANAGER],
		'userView' => [User::ADMIN, User::MANAGER],
		'templateView' => [User::ADMIN, User::MANAGER, User::CLIENT],
		'templateEdit' => [User::ADMIN, User::MANAGER],
		'userAddManager' => [User::CLIENT, User::VIEWER],
		'transmitRights' => [/*debug*/User::ADMIN, User::MANAGER],
		'management' => [User::ADMIN, User::MANAGER],
		'admin' => [User::ADMIN]
	);
	
	public static function groups() {
		return self::$permissions[self::AUTH];
	}
	
	public static function check($group) {
		$user_group = !is_null(Application::$user) ? Application::$user->group : User::ANONYMOUS;
		
		$has_permissions = isset(self::$permissions[$group]);
		
		if (Session::hasGroup() && $group!=self::ADMIN) {
			return $has_permissions && in_array(Session::getGroup(), self::$permissions[$group]) && in_array($user_group, self::$permissions[self::ADMIN]);
		}
		
		return $has_permissions && in_array($user_group, self::$permissions[$group]);
	}
	
}

?>