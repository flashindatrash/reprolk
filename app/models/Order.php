<?php

class Order extends BaseModel {
	
	const INCOMING = 'incoming';
	const CANCELED = 'canceled';
	
	public $id;
	public $uid;
	public $username; //uid->username
	public $title;
	public $id_1c;
	public $number_1c;
	public $status;
	public $pid;
	public $photopolymer_name; //pid->name
	public $form_count;
	public $area;
	public $urgent;
	public $angels;
	public $colorproof;
	public $date_created;
	public $date_changed;
	public $date_due;
	
	public static function tableName() {
		return 'orders';
	}
	
	public function isCanceled() {
		return $this->status==Order::CANCELED;
	}
	
	public function canCancel() {
		return !$this->isCanceled();
	}
	
	public function cancel() {
		return !is_null($this->id) && $this->canCancel() && $this->edit(['status'], [Order::CANCELED]);
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
	
	public static function getAll($fields, $gid = null) {
		$fields[] = self::field('uid');
		$fields[] = self::field('id', null, 'id');
		$fields[] = self::field('username', User::tableName(), 'username');
		
		$join = array();
		$join[] = self::inner('id', User::tableName(), 'uid');
		
		$where = array();
		if (!is_null($gid)) 
			$where[] = self::field('gid', User::tableName()) . ' = ' . $gid;
		
		return self::selectRows($fields, $where, $join);
	}
	
	public static function byId($id, $gid = null) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('username', User::tableName(), 'username');
		$fields[] = self::field('name', Photopolymer::tableName(), 'photopolymer_name');
		
		$join = array();
		$join[] = self::inner('id', User::tableName(), 'uid');
		$join[] = self::inner('id', Photopolymer::tableName(), 'pid');
		
		$where = array();
		$where[] = self::field('id') . ' = ' .$id;
		if (!is_null($gid))
			$where[] = self::field('gid', User::tableName()) . ' = ' . $gid;
		
		return self::selectRow($fields, $where, $join);
	}
	
	public static function add($fields, $values) {
		return self::insertRow($fields, $values);
	}
	
	public static function editById($id, $fields, $values) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::update($fields, $values, $where);
	}
	
}

?>