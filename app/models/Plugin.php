<?php

class Plugin extends BaseModel {
	
	public $name;
	public $files;
	
	const FIELD_NAME = 'name';
	
	public static $fields = array(self::FIELD_NAME, 'route', 'files');
	
	public static function tableName() {
		return 'plugins';
	}
	
	/* Static methods */
	
	public static function clear() {
		return self::truncate();
	}
	
	public static function byName($name) {
		return self::selectRow(null, [self::field(self::FIELD_NAME) . ' = "' . $name . '"']);
	}
	
	public static function add($name, $route, $files) {
		$sFiles = validQuotes(serialize($files));
		return self::insertRow(self::$fields, array($name, $route, $sFiles));
	}
	
	public static function getAll() {
		return self::selectRows([self::field(self::FIELD_NAME)]);
	}
	
}

?>