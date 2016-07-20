<?php

class Plugin extends BaseModel {
	
	public $name;
	public $files;
	
	public static $fields = array('name', 'files');
	
	public static function tableName() {
		return 'plugins';
	}
	
	/* Static methods */
	
	public static function clear() {
		return self::truncate();
	}
	
	public static function byName($name) {
		return self::selectRow(null, [self::field('name') . ' = "' . $name . '"']);
	}
	
	public static function add($name, $files) {
		$sFiles = validQuotes(serialize($files));
		return self::insertRow(self::$fields, array($name, $sFiles));
	}
	
	public static function getAll() {
		return self::selectRows([self::field('name')]);
	}
	
}

?>