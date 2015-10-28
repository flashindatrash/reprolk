<?php

class Photopolymer extends BaseModel {
	
	public $id;
	public $name;
	public $id_1c;
	
	public static function tableName() {
		return 'photopolymers';
	}
	
	public function remove() {
		return !is_null($this->id) && self::delete([self::field('id') . ' = ' . $this->id]);
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		
		return self::selectRow(null, $where);
	}
	
	public static function getAll() {
		return self::selectRows();
	}
	
	public static function add($name, $id_1c) {
		return self::insertRow(['name', 'id_1c'], [$name, $id_1c]);
	}
	
}

?>