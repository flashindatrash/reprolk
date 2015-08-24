<?php

class Route {
	
	public $name;
	public $path;
	public $permission;
	public $hidden;
	
	public function __construct($name, $path = '', $permission = null, $hidden = false) {
		$this->name = $name;
		$this->path = $path;
		$this->permission = $permission;
		$this->hidden = $hidden;
	}
	
	public function getValue() {
		return Application::str('MENU_' . $this->name);
	}
	
	public function isAvailable() {
		return is_null($this->permission) || UserAccess::check($this->permission);
	}
	
	public function isVisible() {
		return !$this->hidden && $this->isAvailable();
	}
	
}

?>