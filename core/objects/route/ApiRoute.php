<?php

class ApiRoute extends Route {
	
	public function __construct($name, $path = '/', $permission = null) {
		parent::__construct($name, $path, $permission, Route::TYPE_HIDDEN);
	}
	
	public function controllerPath() {
		return 'api/' . parent::controllerPath();
	}
	
	//текст для ссылок для API Documentation
	public function linkText() {
		return $this->path;
	}
	
}

?>