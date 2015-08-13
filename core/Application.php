<?php

class Application {

	public $url;
	
	private $dataBaseManager;
    
	public function connect($config) {
		$this->dataBaseManager = new DataBaseManager($config);
	}
	
	public function getContent() {
		print "empty";
	}
	
}

?>