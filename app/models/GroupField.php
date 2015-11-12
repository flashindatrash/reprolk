<?php

class GroupField extends Field {
	
	public $gid;
	public $fid;
	
	public static $fields = array('gid', 'fid');
	
	public static function tableName() {
		return 'group_fields';
	}
	
	public static function getFids($gid) {
		$fields = array();
		$fields[] = self::field('fid');
		
		$where = array();
		$where[] = self::field('gid') . ' = ' . $gid;
		
		return self::selectRows($fields, $where);
	}
	
	public static function getAll($route, $gid) {
		$fields = array();
		foreach (Field::$fields as $field)
			$fields[] = self::field($field, Field::tableName(), $field);
		
		$where = array();
		$where[] = self::field('route', Field::tableName()) . ' = "' . $route . '"';
		$where[] = self::field('system', Field::tableName()) . ' = 0';
		$where[] = self::field('gid') . ' = ' . $gid;
		
		$join = array();
		$join[] = self::inner('id', Field::tableName(), 'fid');
		
		return self::selectRows($fields, $where, $join);
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