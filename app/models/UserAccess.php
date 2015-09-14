<?php

class UserAccess extends BaseModel {
	
	const NONE = 'none';
	const ALL = 'all';
	const AUTH = 'auth';
	
	const ADMIN = 'admin';
	const ORDER_VIEW = 'orderView';
	const ORDER_ADD = 'orderAdd';
	const USER_VIEW = 'userView';
	const USER_ADD = 'userAdd';
	
	public static $permissions = array (
		'none' => [],
		'all' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER, User::ANONYMOUS],
		'auth' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER],
		'orderAdd' => [User::MANAGER, User::CLIENT],
		'orderView' => [User::ADMIN, User::MANAGER, User::CLIENT, User::VIEWER],
		'userView' => [User::ADMIN, User::MANAGER],
		'userAdd' => [User::ADMIN, User::MANAGER],
		'admin' => [User::ADMIN]
	);
	
	public static function groups() {
		return self::$permissions[self::AUTH];
	}
	
	public static function check($group) {
		$user_group = !is_null(Application::$user) ? Application::$user->group : User::ANONYMOUS;
		$has_permissions = isset(self::$permissions[$group]);
		
		if (SystemSession::hasGroup() && $group!=self::ADMIN) {
			return $has_permissions && in_array(SystemSession::getGroup(), self::$permissions[$group]) && in_array($user_group, self::$permissions[self::ADMIN]);
		}
		
		return $has_permissions && in_array($user_group, self::$permissions[$group]);
	}
	
}

?>