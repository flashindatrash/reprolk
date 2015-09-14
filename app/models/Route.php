<?php

class Route {
	
	public $name;
	public $path;
	public $permission;
	public $hidden;
	public $routes;
	
	public function __construct($name, $path = '/', $permission = null, $hidden = false, $routes = null) {
		$this->name = $name;
		$this->path = $path;
		$this->permission = $permission;
		$this->hidden = $hidden;
		$this->routes = is_null($routes) ? [] : $routes;
	}
	
	public function menuName() {
		return Application::str('menu_' . $this->name);
	}
	
	public function menuTitle() {
		return Application::str('menu_title_' . $this->name);
	}
	
	public function isAvailable() {
		return is_null($this->permission) || UserAccess::check($this->permission);
	}
	
	public function isVisible() {
		return !$this->hidden && $this->isAvailable();
	}
	
}

?>