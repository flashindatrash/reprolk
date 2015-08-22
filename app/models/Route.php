<?php

class Route {
	
	public $name;
	public $path;
	public $permission;
	
	public function __construct($name, $path = '', $permission = null) {
		$this->name = $name;
		$this->path = $path;
		$this->permission = $permission;
	}
	
	public function getValue() {
		return Application::str('MENU_' . $this->name);
	}
	
	public function isAvailable() {
		return is_null($this->permission) || UserAccess::check($this->permission);
	}
	
}

?>