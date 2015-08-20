<?php

class UserAccess extends BaseModel {
	
	const ORDER_ADD = 'orderAdd';
	
	private static $permissions = array (
		'orderAdd' => [User::ADMIN, User::MANAGER]
	);
	
	public static function check($p, $group) {
		return isset(self::$permissions[$p]) && in_array($group, self::$permissions[$p]);
	}
	
}

?>