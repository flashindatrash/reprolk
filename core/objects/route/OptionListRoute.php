<?php

class OptionListRoute extends Route {
	
	public function __construct($name, $path = '/', $permission = null, $type = 0, $routes = null) {
		if ($routes==null) {
			$routes = array();
		}
		
		$fields = Field::getCustomized();
		foreach ($fields as $field) {
			$route = new DataRoute(Route::OPTION, $field->name, $path, $permission, $type, null, Route::PATH_FIELDS);
			$route->data = $field->id;
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