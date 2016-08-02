<?php

include_once '../core/objects/SQLOnDuplicate.php';

class Auth extends BaseModel {
	
	public $uid;
	public $auth_key;
	
	const FIELD_KEY		= 'auth_key';
	
	public static function tableName() {
		return 'auth';
	}
	
	//подхачим текущий класс, чтобы все оббертки через selectRow, возвращали нам класс User
	public static function getClassName() {
		return User::getClassName();
	}
	
	public static function update($uid, $key) {
		$duplicate = new SQLOnDuplicate();
		$duplicate->addField(self::field(self::FIELD_KEY));
		return self::insertRow(['uid', self::FIELD_KEY], [$uid, $key], $duplicate);
	}
	
	public static function userByKey($key) {
		$fields = array();
		$fields[] = self::field(self::FIELD_KEY);
		foreach (User::$fields_all as $ufield) {
			$fields[] = self::field($ufield, User::tableName(), $ufield);
		}
		
		$join = array();
		$join[] = self::inner('id', User::tableName(), 'uid');
		
		$where = array();
		$where[] = self::field(self::FIELD_KEY) . ' = "' . $key . '"';
		
		return self::selectRow($fields, $where, $join);
	}
}

?>