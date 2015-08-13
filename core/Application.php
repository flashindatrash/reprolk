<?php

class Application {

	public $url;
	
	public static $db;
    
	public function connect($config) {
		self::$db = new DataBaseManager($config);
	}
	
	public function addResource($name, $path) {
		
	}
	
	public function getContent() {
		print "empty";
	}
	
}

?>