<?php

class GroupPhotopolymer extends BaseModel {
	
	public $gid;
	public $pid;
	public $name; //photopolymer->name
	
	public static $fields = array('gid', 'pid');
	
	public static function tableName() {
		return 'group_photopolymers';
	}
	
	public static function getAll($gid) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('name', Photopolymer::tableName(), 'name');
		
		$join = array();
		$join[] = self::inner('id', Photopolymer::tableName(), 'pid');
		
		$where = array();
		$where[] = self::field('gid') . ' = ' . $gid;
		
		return self::selectRows($fields, $where, $join);
	}
	
	public static function set($group, $photopolymers) {
		$values = array();
		foreach ($photopolymers as $photopolymer) {
			$values[] = array($group, $photopolymer);
		}
		
		$delete = self::delete([self::field('gid') . ' = ' . $group]);
		$insert = self::insertRows(self::$fields, $values);
		return $delete && !is_null($insert);
	}
	
}

?>