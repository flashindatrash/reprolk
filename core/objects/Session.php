<?php

class Session {
	
	const USER_GROUP = 'user_group';
	const USER_GID = 'user_gid';
	
	public static function hasGroup() {
		return !is_null(Application::$user) && hasSession(self::USER_GROUP);
	}
	
	public static function getGroup() {
		return self::hasGroup() ? session(self::USER_GROUP) : null;
	}
	
	public static function setGroup($value) {
		$_SESSION[self::USER_GROUP] = $value;
	}
	
	public static function hasGid() {
		return !is_null(Application::$user) && hasSession(self::USER_GID);
	}
	
	public static function getGid() {
		return self::hasGid() ? session(self::USER_GID) : null;
	}
	
	public static function setGid($value) {
		$_SESSION[self::USER_GID] = $value;
	}
	
	public static function clear() {
		self::setGroup(null);
		self::setGid(null);
	}
	
}

?>