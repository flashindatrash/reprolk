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
	
	public static function getName() {
		return self::isLogined() ? Application::$user->username : 'anonymous';
	}
	
	public static function getEmail() {
		return self::isLogined() ? Application::$user->email : null;
	}
	
	public static function getPassword() {
		return self::isLogined() ? Application::$user->password : null;
	}
	
	public static function getAuthKey() {
		return self::isLogined() ? Application::$user->auth_key : null;
	}
	
	public static function getLang() {
		return self::isLogined() ? Application::$user->lang : Locale::DEFAULT_LANGUAGE;
	}
	
	public static function setLang($value) {
		return self::isLogined() ? Application::$user->editLang($value) : false;
	}
	
}

?>