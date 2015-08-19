<?php

class User extends BaseModel {

	public $id;
	public $group;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	
	public static function byId($id) {
		return Application::$db->selectRow('users', '*', '`id` = ' . $id, 'User');
	}
	
	public static function login($email, $password) {
		return Application::$db->selectRow('users', '*', '`email` = "' . $email . '" and `password` = "' . $password . '"', 'User');
	}
	
}

?>