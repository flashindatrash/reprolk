<?php

class Comment extends BaseModel {
	
	const FORMAT_DATE = 'Y-m-d H:i:s';
	const EDIT_DELAY = 5*60;
	
	public $id;
	public $oid;
	public $uid;
	public $message;
	public $date;
	public $status;
	
	public $username; //user->username
	
	const FIELD_ID = 'id'; //comment id
	const FIELD_OID = 'oid'; //order id
	const FIELD_UID = 'uid'; //user id
	const FIELD_MESSAGE = 'message';
	const FIELD_DATE = 'date';
	const FIELD_STATUS = 'status';
	
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
		return !is_null($this->id) && self::delete([self::field(Comment::FIELD_ID) . ' = ' . $this->id]);
	}
	
	public static function getAll($oid) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field(User::FIELD_USERNAME, User::tableName(), User::FIELD_USERNAME);
		
		$join = array();
		$join[] = self::inner(User::FIELD_ID, User::tableName(), Comment::FIELD_UID);
		
		$where = array();
		$where[] = self::field(Comment::FIELD_OID) . ' = ' . $oid;
		
		return self::selectRows($fields, $where, $join, new SQLOrderBy(Comment::FIELD_DATE));
	}
	
	public static function byId($id) {
		//дабы запретить вытаскивать чужие комменты, добавим выборку по FIELD_UID
		$where = array();
		$where[] = self::field(Comment::FIELD_ID) . ' = ' . $id;
		$where[] = self::field(Comment::FIELD_UID) . ' = ' . Account::getId();
		return self::selectRow(null, $where);
	}
	
	public static function add($oid, $message, $status = '') {
		$fields = [Comment::FIELD_OID, Comment::FIELD_UID, Comment::FIELD_MESSAGE, Comment::FIELD_DATE, Comment::FIELD_STATUS];
		$values = [$oid, Account::getId(), $message, date(Comment::FORMAT_DATE), $status];
		return self::insertRow($fields, $values);
	}
	
}

?>