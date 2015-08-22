<?php

class UserAccess extends BaseModel {
	
	const ORDER_ADD = 'orderAdd';
	const USER_GET = 'userGet';
	const USER_ADD = 'userAdd';
	
	private static $permissions = array (
		'orderAdd' => [User::ADMIN, User::MANAGER],
		'userGet' => [User::ADMIN, User::MANAGER, User::CLIENT],
		'userAdd' => [User::ADMIN],
	);
	
	public static function check($group) {
		return 
			!is_null(Application::$user) && 
			isset(self::$permissions[$group]) && 
			in_array(Application::$user->group, self::$permissions[$group]);
	}
	
}

?>