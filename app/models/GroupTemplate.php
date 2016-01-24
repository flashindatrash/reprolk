<?php

class GroupTemplate extends BaseModel {
	
	public $tid;
	public $fid;
	public $value;
	public $name; //fid -> Field::name
	
	public static $fields = array('tid', 'fid', 'value');
	
	public static function tableName() {
		return 'group_templates';
	}
	
	public static function getAll($tid) {
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field('name', Field::tableName(), 'name');
		
		$join = array();
		$join[] = self::inner('id', Field::tableName(), 'fid');
		
		$where = array();
		$where[] = self::field('tid') . ' = ' . $tid;
		return self::selectRows($fields, $where, $join);
	}
	
	public static function save($tid, $fields, $values) {
		$rows = array();
		
		for ($i = 0; $i<count($fields); $i++) {
			$rows[] = array($tid, $fields[$i], $values[$i]);
		}
		
		$delete = self::delete([self::field('tid') . ' = ' . $tid]);
		$insert = self::insertRows(self::$fields, $rows);
		return $delete && !is_null($insert);
	}
	
}

?>