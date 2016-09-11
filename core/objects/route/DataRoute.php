<?php

class DataRoute extends Route {
	
	public $data;
	public $route;
	
	public function __construct($route, $name, $path = '/', $permission = null, $type = 0, $routes = null, $controllerPath = '') {
		parent::__construct($route . '_' . $name, $path . '/' . $name, $permission, $type, $routes, $controllerPath);
		$this->route = $route;
	}

	//имя контроллера
	public function controllerName() {
		return $this->route . 'Controller';
	}
	
}

?>