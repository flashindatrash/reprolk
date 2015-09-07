<?php

class User extends BaseModel {
	
	const ANONYMOUS	= 'anonymous';
	const CLIENT	= 'client';
	const MANAGER	= 'manager';
	const ADMIN		= 'admin';
	
	public $id;
	public $group;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	public $uid;
	
	public static $fields_view = array('firstname', 'lastname', 'email', 'group');
	
	public static function byId($id) {
		return Application::$db->selectRow('users', '*', '`id` = ' . $id, 'User');
	}
	
	public static function login($email, $password) {
		return Application::$db->selectRow('users', '*', '`email` = "' . $email . '" and `password` = "' . $password . '"', 'User');
	}
	
	public static function add($fields, $values) {
		return Application::$db->insert('users', $fields, $values);
	}
	
	public static function getAll($group = null) {
		return Application::$db->selectRows('users', DataBaseManager::array2fields(self::$fields_view), is_null($group) ? '1' : '`group` = "' . $group . '"', 'User', '0, 300');
	}
}

?>