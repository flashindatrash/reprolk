<?php

class Field extends BaseModel {
	
	public $id;
	public $route;
	public $name;
	public $type;
	public $system;
	public $mandatory;
	public $templated;
	public $weight;
	
	public $session; //то, что хранится в сессии (в POST например)
	
	public static function tableName() {
		return 'fields';
	}
	
	public static $fields = array('id', 'route', 'name', 'type', 'system', 'mandatory', 'templated', 'weight');
	
	public static function tableNameByRoute($route) {
		switch ($route) {
			case Route::ORDER_ADD: return Order::tableName();
		}
		return null;
	}
	
	public static function dbType($type, $data, $length = 255) {
		switch($type) {
			case 'checkbox':
				return 'tinyint(1)';
			case 'date':
				return 'datetime';
			case 'select':
				$items = explode(',', stripQuotes($data));
				if (count($items)==0) return 'varchar(' . $length . ')';
				return 'ENUM(\'' . join('\',\'', $items) . '\')';
			case 'number':
				return 'int(16)';
			default:
				return 'varchar(' . $length . ')';
		}
	}
	
	public static function dbDefault($type, $default) {
		if (strlen($default)==0) return null;
		switch ($type) {
			case 'checkbox':
				return toBool($default) ? '1' : '0';
			case 'number':
				return int($default);
			case 'text':
				return $default;
			default:
				return null;
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
	
	public function isTemplated() {
		return $this->templated==1;
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
	
	public function canUse() {
		return $this->type!='hidden';
	}
	
	/*
		actions
	*/
	
	private $column;
	public function getColumn() {
		if (is_null($this->column)) {
			$table = self::tableNameByRoute($this->route);
			if (is_null($table)) return null;
			$this->column = self::column($this->name, $table);
		}
		return $this->column;
	}
	
	//варианты для заполнения
	public $option; //для указании кастомных вариантов
	public function getOption() {
		if (!is_null($this->option)) {
			return $this->option;
		}
		
		switch($this->type) {
			case 'select':
			case 'multiple':
				$column = $this->getColumn();
				return !is_null($column) ? $column->enum() : [];
			default:
				return '';
		}
	}
	
	//дефолтное значение
	public function getDefault() {
		$column = $this->getColumn();
		return !is_null($column) ? $column->Default : null;
	}
	
	public function getValue() {
		return is_null($this->session) ? $this->getDefault() : $this->session;
	}
	
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
		return !is_null($column) ? $column->enum() : [];
	}
	
	public static function getRoutes() {
		$column = self::column('route');
		return !is_null($column) ? $column->enum() : [];
	}
	
	/*
		selects
	*/
	
	public static function byId($id) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::selectRow(null, $where);
	}
	
	public static function getAll($route, $showCommon = true, $showSystem = true) {
		$where = array();
		$where[] = self::field('route') . ' = "' . $route . '"';
		if (!$showCommon) $where[] = self::field('system') . ' = 1';
		else if (!$showSystem) $where[] = self::field('system') . ' = 0';
		return self::selectRows(null, $where, null, new SQLOrderBy('weight', 'asc'));
	}
	
	public static function add($route, $type, $name, $value, $mandatory, $weight, $default) {
		$table = self::tableNameByRoute($route);
		if (is_null($table)) return null;
		
		$columns = self::columns($table);
		
		foreach ($columns as $column) {
			if ($column->Field==$name) return null;
		}
		
		if (!self::addColumn($name, self::dbType($type, $value), $mandatory, self::dbDefault($type, $default), $table)) {
			return null;
		}
		
		$fields = ['route', 'name', 'type', 'mandatory', 'weight'];
		$values = [$route, $name, $type, $mandatory, $weight];
		return self::insertRow($fields, $values);
	}
	
	public static function updateWeight($id, $weight) {
		return self::editById($id, ['weight'], [$weight]);
	}
	
	public static function editById($id, $fields, $values) {
		$where = array();
		$where[] = self::field('id') . ' = ' . $id;
		return self::update($fields, $values, $where);
	}
	
}

?>