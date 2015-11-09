<?php

class GroupField extends Field {
	
	public $gid;
	public $fid;
	
	public static $fields = array('gid', 'fid');
	
	public static function tableName() {
		return 'group_fields';
	}
	
	public static function getAll($gid) {
		$fields = array();
		$fields[] = self::field('fid');
		
		$where = array();
		$where[] = self::field('gid') . ' = ' . $gid;
		
		return self::selectRows($fields, $where);
	}
	
	public static function set($gid, $fields) {
		$values = array();
		foreach ($fields as $field) {
			$values[] = array($gid, $field);
		}
		
		$delete = self::delete([self::field('gid') . ' = ' . $gid]);
		$insert = self::insertRows(self::$fields, $values);
		return $delete && !is_null($insert);
	}
	
}

?>