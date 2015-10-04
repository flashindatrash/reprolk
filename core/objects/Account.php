<?php

class Account {
	
	public static function isLogined() {
		return !is_null(Application::$user);
	}
	
	public static function getRawGroup() {
		return self::isLogined() ? Application::$user->group : User::ANONYMOUS;
	}
	
	public static function getGroup() {
		return Session::hasGroup() ? Session::getGroup() : self::getRawGroup();
	}
	
	public static function setGroup($value) {
		return Application::$user->editGroup($value);
	}
	
	public static function getRawGid() {
		return self::isLogined() ? Application::$user->gid : -1;
	}
	
	public static function getGid() {
		return Session::hasGid() ? Session::getGid() : self::getRawGid();
	}
	
	public static function getId() {
		return Application::$user->id;
	}
	
	public static function isAdmin() {
		return self::getRawGroup() == User::ADMIN;
	}
	
}

?>