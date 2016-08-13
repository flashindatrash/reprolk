<?php

class File extends BaseModel {
	
	public $id;
	public $name;
	public $size;
	public $type;
	public $oid;
	public $cid;
	public $content;
	public $uid; //order->uid
	public $gid; //user->gid
	
	const EXT_PHP = 'php';
	
	public static function tableName() {
		return 'files';
	}
	
	public function isComment() {
		return $this->cid>0;
	}
	
	public function isValid() {
		return $this->size>0 && strlen($this->content)>0;
	}
	
	public function extension() {
		return pathinfo($this->name, PATHINFO_EXTENSION);
	}
	
	public static function byId($id) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('uid', Order::tableName(), 'uid');
		$fields[] = self::field('gid', User::tableName(), 'gid');
		
		$join = array();
		$join[] = self::inner('id', Order::tableName(), 'oid');
		$join[] = self::inner('id', User::tableName(), 'uid', Order::tableName());
		
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		
		return self::selectRow($fields, $where, $join);
	}
	
	public static function add($files, $oid, $cid = 0) {
		$values = array();
		foreach ($files as $file) {
			$values[] = array($file->name, $file->size, $file->type, $file->content, $oid, $cid);
		}
		
		return count($values)>0 && self::insertRows(array('name', 'size', 'type', 'content', 'oid', 'cid'), $values);
	}
	
	public static function getAll($oid) {
		$where = array();
		$where[] = self::field('oid') . ' = ' . $oid;
		return self::selectRows(null, $where);
	}
	
	public static function editByOrder($oid, $fields, $values) {
		$where = array();
		$where[] = self::field('oid') . ' = ' . $oid;
		return self::update($fields, $values, $where);
	}
	
	public static function uploadMaxFilesize() {
		return self::returnBytes(ini_get('upload_max_filesize'));
	}
	
	public static function postMaxSize() {
		return self::returnBytes(ini_get('post_max_size'));
	}
	
	private static function returnBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// Модификатор 'G' доступен, начиная с PHP 5.1.0
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}
	
}