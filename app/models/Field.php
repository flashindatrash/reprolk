<?php

class Field extends BaseModel {
	
	public $id;
	public $route;
	public $name;
	public $type;
	public $value;
	public $system;
	public $mandatory;
	public $weight;
	
	public $session; //то, что хранится в сессии (в POST например)
	
	public static function tableName() {
		return 'fields';
	}
	
	/*
		is...
	*/
	
	public function isMandatory() {
		return $this->mandatory==1;
	}
	
	public function isSystem() {
		return $this->system==1;
	}
	
	/*
		can...
	*/
	
	public function canDelete() {
		return !$this->isSystem();
	}
	
	/*
		actions
	*/
	
	public function remove() {
		return $this->canDelete() && !is_null($this->id) && self::delete([self::field('id') . ' = ' . $this->id]);
	}
	
	/*
		enums
	*/
	
	public static function getTypes() {
		$column = self::column('type');
		return !is_null($column) ? $column->enum() : array();
	}
	
	public static function getRoutes() {
		$column = self::column('route');
		return !is_null($column) ? $column->enum() : array();
	}
	
	/*
		selects
	*/
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::selectRow(null, $where);
	}
	
	public static function getAll($route) {
		$where = array();
		$where[] = self::field('route') . ' = "' . $route . '"';
		
		return self::selectRows(null, $where, null, new SQLOrderBy('weight', 'asc'));
	}
	
}

?>