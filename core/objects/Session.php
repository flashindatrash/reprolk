<?php

class Session {
	
	const USER_ID = 'user_id';
	const USER_GROUP = 'user_group';
	const USER_GID = 'user_gid';
	
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
		self::setId(null);
		self::setGroup(null);
		self::setGid(null);
	}
	
}

?>