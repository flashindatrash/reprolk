<?php

class GroupPhotopolymer extends BaseModel {
	
	public $gid;
	public $pid;
	public $name; //photopolymer->name
	
	const FIELD_GID = 'gid';
	const FIELD_PID = 'pid';
	
	public static $fields = array(self::FIELD_GID, self::FIELD_PID);
	
	public static function tableName() {
		return 'group_photopolymers';
	}
	
	public static function getAll($gid = null) {
		if (is_null($gid)) {
			$gid = Account::getGid();
		}
		
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('name', Photopolymer::tableName(), 'name');
		
		$join = array();
		$join[] = self::inner('id', Photopolymer::tableName(), self::FIELD_PID);
		
		$where = array();
		$where[] = self::field('gid') . ' = ' . $gid;
		
		return self::selectRows($fields, $where, $join);
	}
	
	public static function set($gid, $photopolymers) {
		$values = array();
		foreach ($photopolymers as $photopolymer) {
			$values[] = array($gid, $photopolymer);
		}
		
		$delete = self::delete([self::field(self::FIELD_GID) . ' = ' . $gid]);
		$insert = self::insertRows(self::$fields, $values);
		return $delete && !is_null($insert);
	}
	
}

?>