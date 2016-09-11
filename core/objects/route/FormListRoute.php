<?php

class FormListRoute extends Route {
	
	public function __construct($name, $path = '/', $permission = null, $type = 0, $routes = null) {
		$pages = Field::getRoutes();
		if ($routes==null) {
			$routes = array();
		}
		foreach ($pages as $page) {
			$route = new DataRoute(Route::FIELD, $page, $path, $permission, $type, null, Route::PATH_FIELDS);
			$route->data = strtolower($page);
			$routes[] = $route;
		}
		
		parent::__construct($name, $path, $permission, $type, $routes);
	}
	
	//путь контроллера
	public function controllerPath() {
		return 'base/' . parent::controllerPath();
	}
	
	//имя контроллера
	public function controllerName() {
		return 'CatalogController';
	}
}

?>