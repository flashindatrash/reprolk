<?php

class Comment extends BaseModel {
	
	const FORMAT_DATE = 'Y-m-d H:i:s';
	const EDIT_DELAY = 5*60;
	
	public $id;
	public $oid; //order id
	public $uid; //user id
	public $username; //user->username
	public $message;
	public $date;
	public $status; //order->status
	
	public static function tableName() {
		return 'comments';
	}
	
	public function dateEditExpired() {
		return date(Comment::FORMAT_DATE, strtotime($this->date) + Comment::EDIT_DELAY);
	}
	
	public function isOwner() {
		return $this->uid == Account::getId();
	}
	
	public function isEditExpired() {
		return date(Comment::FORMAT_DATE)>=$this->dateEditExpired();
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
		
		return self::selectRows($fields, $where, $join, new SQLOrderBy(self::field('date')));
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		$where[] = self::field('uid') . ' = ' . Account::getId();
		return self::selectRow(null, $where);
	}
	
	public static function add($order, $message, $status = '') {
		return self::insertRow(['oid', 'uid', 'message', 'date', 'status'], [$order, Account::getId(), $message, date(Comment::FORMAT_DATE), $status]);
	}
	
}

?>