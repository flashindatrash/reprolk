<?php

class User extends BaseModel {
	
	const ANONYMOUS	= 'anonymous';
	const VIEWER	= 'viewer';
	const CLIENT	= 'client';
	const MANAGER	= 'manager';
	const ADMIN		= 'admin';
	
	public $id;
	public $group;
	public $email;
	public $password;
	public $username;
	public $uid;
	
	public static $fields_mandatory = array('email', 'password', 'username', 'group', 'gid');
	
	public static function byId($id) {
		return Application::$db->selectRow('users', '*', '`id` = ' . $id, 'User');
	}
	
	public static function login($email, $password) {
		return Application::$db->selectRow('users', '*', '`email` = "' . $email . '" and `password` = "' . $password . '"', 'User');
	}
	
	public static function add($fields, $values) {
		return Application::$db->insertRow('users', $fields, $values);
	}
	
	public static function getAll($fields, $group = null) {
		return Application::$db->selectRows('users', DataBaseManager::array2fields($fields), is_null($group) ? '1' : '`group` = "' . $group . '"', 'User', '0, 300');
	}
}

?>