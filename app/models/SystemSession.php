<?php

class SystemSession {
	
	const USER_ID = 'user_id';
	const USER_GROUP = 'user_group';
	
	public static function hasId() {
		return hasSession(self::USER_ID);
	}
	
	public static function getId() {
		return session(self::USER_ID);
	}
	
	public static function setId($value) {
		$_SESSION[self::USER_ID] = $value;
	}
	
	public static function hasGroup() {
		return !is_null(Application::$user) && hasSession(self::USER_GROUP);
	}
	
	public static function getGroup() {
		return session('user_group');
	}
	
	public static function setGroup($value) {
		$_SESSION[self::USER_GROUP] = $value;
	}
	
	public static function clear() {
		self::setId(null);
		self::setGroup(null);
	}
	
}

?>