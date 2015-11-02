<?php

class Field extends BaseModel {
	
	public $route;
	public $name;
	public $type;
	public $value;
	public $state;
	public $mandatory;
	public $weight;
	
	public $session; //то, что хранится в сессии (в POST например)
	
	public static function tableName() {
		return 'fields';
	}
	
	public function isMandatory() {
		return $this->mandatory==1;
	}
	
	public function isSystem() {
		return $this->state==2;
	}
	
	public function isCommon() {
		return $this->state==1;
	}
	
	public static function getTypes() {
		$column = self::column('type');
		return !is_null($column) ? $column->enum() : array();
	}
	
	public static function getRoutes() {
		$column = self::column('route');
		return !is_null($column) ? $column->enum() : array();
	}
	
	public static function getAll($route) {
		$where = array();
		$where[] = self::field('route') . ' = "' . $route . '"';
		
		return self::selectRows(null, $where, null, new SQLOrderBy('weight', 'asc'));
	}
	
}

?>