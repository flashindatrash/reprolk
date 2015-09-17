<?php

include '../core/interfaces/ICatalog.php';

class AdminController extends BaseController implements ICatalog {
	
	public function getRoutes() {
		return Application::$routes->current->routes;
	}
	
	public function getContent() {
		$this->pick('system/catalog');
	}
	
}

?>