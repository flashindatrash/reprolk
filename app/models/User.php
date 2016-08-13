<?php

class User extends BaseModel {
	
	const ANONYMOUS	= 'anonymous';
	const VIEWER	= 'viewer';
	const CLIENT	= 'client';
	const MANAGER	= 'manager';
	const ADMIN		= 'admin';
	
	const FIELD_ID = 'id';
	const FIELD_GROUP = 'group';
	const FIELD_EMAIL = 'email';
	const FIELD_PASSWORD = 'password';
	const FIELD_USERNAME = 'username';
	const FIELD_LANG = 'lang';
	const FIELD_GID = 'gid';
	
	public $id;
	public $group;
	public $email;
	public $password;
	public $username;
	public $gid;
	public $lang;
	public $auth_key; //auth->auth_key
	
	public static $fields_all = array(User::FIELD_ID, User::FIELD_EMAIL, User::FIELD_PASSWORD, User::FIELD_USERNAME, User::FIELD_GROUP, User::FIELD_GID, User::FIELD_LANG);
	public static $fields_mandatory = array(User::FIELD_EMAIL, User::FIELD_PASSWORD, User::FIELD_USERNAME, User::FIELD_GROUP, User::FIELD_GID);
	
	public static function tableName() {
		return 'users';
	}
	
	public function editGroup($group) {
		return $this->edit([self::field(User::FIELD_GROUP)], [$group]);
	}
	
	public function editLang($lang) {
		return $this->edit([User::FIELD_LANG], [$lang]);
	}
	
	public function edit($fields, $values) {
		if (is_null($this->id)) return false;
		
		$success = self::editById($this->id, $fields, $values);
		if ($success) {
			foreach ($fields as $i => $field) {
				$this->$field = $values[$i];
			}
		}
		return $success;
	}
	
	public static function byId($id) {
		return self::selectRow(User::$fields_all, [self::field(User::FIELD_ID) . ' = ' . $id]);
	}
	
	public static function login($email, $password) {
		$where = array();
		$where[] = self::field(User::FIELD_EMAIL) . ' = "' . $email . '"';
		$where[] = self::field(User::FIELD_PASSWORD) . ' = "' . $password . '"';
		return self::selectRow(User::$fields_all, $where);
	}
	
	public static function add($fields, $values) {
		return self::insertRow($fields, $values);
	}
	
	public static function editGroupById($id, $group) {
		return self::editById($id, [self::field(User::FIELD_GROUP)], [$group]);
	}
	
	public static function editById($id, $fields, $values) {
		$where = array();
		$where[] = self::field(User::FIELD_ID) . ' = ' . $id;
		return self::update($fields, $values, $where);
	}
	
	public static function getAllGroups() {
		return self::selectRows([User::FIELD_GID, User::FIELD_USERNAME], null, null, new SQLOrderBy(User::FIELD_GROUP, 'desc', User::FIELD_GID));
	}
	
	public static function getAll($fields, $group = null, $gid = null) {
		$where = array();
		if (!is_null($group)) 
			$where[] = self::field(SELF::FIELD_GROUP) . ' = "' . $group . '"';
		if (!is_null($gid)) 
			$where[] = self::field(SELF::FIELD_GID) . ' = ' . $gid;
		
		return self::selectRows($fields, $where);
	}
	
}

?>