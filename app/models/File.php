<?php

class File extends BaseModel {
	
	public $id;
	public $name;
	public $oid;
	public $cid;
	
	public static $fields = array('name', 'oid');
	
	public static function tableName() {
		return 'files';
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		
		return self::selectRow(null, $where);
	}
	
	public static function add($files, $oid) {
		$values = array();
		foreach ($files as $file) {
			$values[] = array($file, $oid);
		}
		
		return self::insertRows(self::$fields, $values);
	}
	
}

?>