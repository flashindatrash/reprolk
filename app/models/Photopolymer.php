<?php

class Photopolymer extends BaseModel {
	
	public $id;
	public $name;
	
	public static function tableName() {
		return 'photopolymers';
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		
		return self::selectRow(null, $where);
	}
	
	public static function getAll() {
		return self::selectRows();
	}
	
	public static function add($name) {
		return self::insertRow(['name'], [$name]);
	}
	
}

?>