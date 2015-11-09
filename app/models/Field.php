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
	
	public static function tableNameByRoute($route) {
		switch ($route) {
			case Route::ORDER_ADD: return Order::tableName();
		}
		return null;
	}
	
	public static function dbType($type, $length = 255) {
		switch($type) {
			case 'checkbox':
				return 'tinyint(1)';
			case 'date':
				return 'datetime';
			default:
				return 'varchar(' . $length . ')';
		}
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
	
	public function canBind() {
		return !$this->isSystem();
	}
	
	/*
		actions
	*/
	
	public function remove() {
		$table = self::tableNameByRoute($this->route);
		
		return $this->canDelete() && 
				!is_null($table) && 
				!is_null($this->id) && 
				!is_null($this->name) && 
				self::delete([self::field('id') . ' = ' . $this->id]) &&
				self::dropColumn($this->name, $table);
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
	
	public static function getAll($route, $ignoreSystem = false, $gid = null) {
		$where = array();
		$where[] = self::field('route') . ' = "' . $route . '"';
		if ($ignoreSystem) $where[] = self::field('system') . ' = 0';
		
		return self::selectRows(null, $where, null, new SQLOrderBy('weight', 'asc'));
	}
	
	public static function add($route, $name, $type, $mandatory, $weight) {
		$table = self::tableNameByRoute($route);
		if (is_null($table)) return null;
		
		$columns = self::columns($table);
		
		foreach ($columns as $column) {
			if ($column->Field==$name) return null;
		}
		
		if (!self::addColumn($name, self::dbType($type), $mandatory, $table)) {
			return null;
		}
		
		$fields = ['route', 'name', 'type', 'mandatory', 'weight'];
		$values = [$route, $name, $type, $mandatory, $weight];
		return self::insertRow($fields, $values);
	}
	
}

?>