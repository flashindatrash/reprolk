<?php

class UserHistory extends BaseModel {
	
	public $id;
	public $uid;
	public $time;
	public $route;
	
	public $username; //uid -> User::username
	
	const FIELD_UID = 'uid';
	const FIELD_TIME = 'time';
	const FIELD_ROUTE = 'route';
	
	public static $fields_all = array(self::FIELD_UID, self::FIELD_TIME, self::FIELD_ROUTE);
	
	public static function tableName() {
		return 'user_history';
	}
	
	/* Static methods */
	
	public static function add($route) {
		$fields = array();
		$values = array();
		
		$fields[] = UserHistory::FIELD_UID;
		$values[] = Account::getId();
		
		$fields[] = UserHistory::FIELD_TIME;
		$values[] = date('Y-m-d H:i:s');
		
		$fields[] = UserHistory::FIELD_ROUTE;
		$values[] = $route;
		
		return self::insertRow($fields, $values);
	}
	
	public static function getAll($count = 30) {
		$fields = self::$fields_all;
		$fields[] = self::field(User::FIELD_USERNAME, User::tableName(), User::FIELD_USERNAME);
		
		$join = array();
		$join[] = self::inner(User::FIELD_ID, User::tableName(), UserHistory::FIELD_UID);
		
		return self::selectRows($fields, null, $join, new SQLOrderBy(UserHistory::FIELD_TIME, SQLOrderBy::DESC), '0, ' . $count);
	}
	
}

?>