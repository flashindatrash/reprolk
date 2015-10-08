<?php

class BaseModel {
	
	public function __construct() {
		
	}
	
	public function toArray() {
		return objectToArray($this);
	}
	
	public static function tableName() {
		return NULL;
	}
	
	protected static function getClassName() {
		return get_called_class();
	}
	
	protected static function selectRow($select = NULL, $where = NULL, $join = NULL) {
		return Application::$db->selectRow(static::tableName(), static::getClassName(), $select, $where, $join);
	}
	
	protected static function selectRows($select = NULL, $where = NULL, $join = NULL, $order = NULL, $range = NULL) {
		return Application::$db->selectRows(static::tableName(), static::getClassName(), $select, $where, $join, $order, $range);
	}
	
	protected static function delete($where = NULL) {
		return Application::$db->delete(static::tableName(), $where);
	}
	
	protected static function insertRow($fields, $values) {
		return Application::$db->insertRow(static::tableName(), $fields, $values);
	}
	
	protected static function insertRows($fields, $values) {
		return Application::$db->insertRows(static::tableName(), $fields, $values);
	}
	
	protected static function update($fields, $values, $where = NULL) {
		return Application::$db->update(static::tableName(), $fields, $values, $where);
	}
	
	protected static function table($table_name) {
		return Application::$db->tableName($table_name);
	}
	
	protected static function field($field, $table_name = NULL, $as = NULL) {
		if (is_null($table_name)) $table_name = static::tableName();
		return static::table($table_name) . '.' . $field . (is_null($as) ? '' : ' as ' . $as);
	}
	
	protected static function inner($joinParam, $joinTable, $destParam, $destTable = NULL) {
		if (is_null($destTable)) $destTable = static::tableName();
		return 'inner join ' . static::table($joinTable) . ' on ' . static::table($destTable) . '.' . $destParam . '=' . static::table($joinTable) . '.' . $joinParam;
	}
	
}

?>