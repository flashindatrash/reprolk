<?php

class Comment extends BaseModel {
	
	public $id;
	public $oid;
	public $uid;
	public $username; //user->username
	public $message;
	public $date;
	
	public static function tableName() {
		return 'comments';
	}
	
	public function remove() {
		return !is_null($this->id) && self::delete([self::field('id') . ' = ' . $this->id]);
	}
	
	public static function getAll($order) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('username', User::tableName(), 'username');
		
		$join = array();
		$join[] = self::inner('id', User::tableName(), 'uid');
		
		$where = array();
		$where[] = self::field('oid') . ' = ' . $order;
		
		return self::selectRows($fields, $where, $join, new SQLOrderBy(self::field('date'));
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		$where[] = self::field('uid') . ' = ' . Account::getId();
		return self::selectRow(null, $where);
	}
	
	public static function add($order, $message) {
		return self::insertRow(['oid', 'uid', 'message', 'date'], [$order, Account::getId(), $message, date("Y-m-d H:i:s")]);
	}
	
}

?>