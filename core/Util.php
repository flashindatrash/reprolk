<?php

class Util {
	
	public static function inc($type, $file) {
		$config = Application::$config;
		if (is_null($config) || is_null($type) || !isset($config['app'][$type])) return;
		$path = $config['app'][$type] . $file;
		if (file_exists($path)) {
			include_once($path);
		}
	}
	
}

?>