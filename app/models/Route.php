<?php

class Route {
	
	public $name;
	public $path;
	public $inMenu;
	
	public function __construct($name, $path = '', $inMenu = true) {
		$this->name = $name;
		$this->path = $path;
		$this->inMenu = $inMenu;
	}
	
}

?>