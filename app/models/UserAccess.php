<?php

class UserAccess extends BaseModel {
	
	const NONE = 'none';
	const ALL = 'all';
	const AUTH = 'auth';
	
	const ORDER_ADD = 'orderAdd';
	const USER_ADD = 'userAdd';
	const USER_ALL = 'userAll';
	
	public static $permissions = array (
		'none' => [],
		'auth' => [User::ADMIN, User::MANAGER, User::CLIENT],
		'all' => [User::ADMIN, User::MANAGER, User::CLIENT, User::ANONYMOUS],
		'orderAdd' => [User::ADMIN, User::MANAGER],
		'userAdd' => [User::ADMIN],
		'userAll' => [User::ADMIN],
	);
	
	public static function check($group) {
		$user_group = !is_null(Application::$user) ? Application::$user->group : User::ANONYMOUS;
		return isset(self::$permissions[$group]) && in_array($user_group, self::$permissions[$group]);
	}
	
}

?>