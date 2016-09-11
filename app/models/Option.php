<?php

class Option extends BaseModel {
	
	public $id;
	public $fid;
	public $name;
	public $id_1c;

	const FIELD_ID = 'id';
	const FIELD_FID = 'fid';
	const FIELD_NAME = 'name';
	const FIELD_ID_1C = 'id_1c';
	
	public static function tableName() {
		return 'options';
	}
	
	public function remove() {
		return !is_null($this->id) && self::delete([self::field(Option::FIELD_ID) . ' = ' . $this->id]);
	}
	
	public function save() {
		$this->id = self::insertRow([Option::FIELD_FID, Option::FIELD_NAME, Option::FIELD_ID_1C], [$this->fid, $this->name, $this->id_1c]);
		return !is_null($this->id);
	}
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field(Option::FIELD_ID) . ' = ' . $id;
		
		return self::selectRow(null, $where);
	}
	
	public static function getAll($fid) {
		return self::selectRows(null, [self::field(Option::FIELD_FID) . ' = ' . $fid]);
	}
	
}

?>