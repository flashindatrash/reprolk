<?php

class Route {
	
	const INDEX = 'Index';
	const LOGIN = 'Login';
	const LOGOUT = 'Logout';
	const NOT_FOUND = 'NotFound';
	const ACCESS_DENIED = 'AccessDenied';
	
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
	
	public function linkText() {
		return Application::str('menu_' . $this->name);
	}
	
	public function linkTitle() {
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