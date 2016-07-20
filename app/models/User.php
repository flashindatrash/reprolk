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
	public $gid;
	public $lang;
	
	public static $fields_mandatory = array('email', 'password', 'username', 'group', 'gid');
	
	public static function tableName() {
		return 'users';
	}
	
	public function editGroup($group) {
		return $this->edit([self::field('group')], [$group]);
	}
	
	public function editLang($lang) {
		return $this->edit(['lang'], [$lang]);
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
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::selectRow(null, $where);
	}
	
	public static function login($email, $password) {
		$where = array();
		$where[] = self::field('email') . ' = "' . $email . '"';
		$where[] = self::field('password') . ' = "' . $password . '"';
		
		return self::selectRow(null, $where);
	}
	
	public static function add($fields, $values) {
		return self::insertRow($fields, $values);
	}
	
	public static function editGroupById($id, $group) {
		return self::editById($id, [self::field('group')], [$group]);
	}
	
	public static function editById($id, $fields, $values) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::update($fields, $values, $where);
	}
	
	public static function getAllGroups() {
		return self::selectRows(['gid', 'username'], null, null, new SQLOrderBy('`group`', 'desc', 'gid'));
	}
	
	public static function getAll($fields, $group = null, $gid = null) {
		$where = array();
		if (!is_null($group)) 
			$where[] = self::field('group') . ' = "' . $group . '"';
		if (!is_null($gid)) 
			$where[] = self::field('gid') . ' = ' . $gid;
		
		return self::selectRows($fields, $where);
	}
	
}

?>