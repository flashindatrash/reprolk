<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'ICatalog.php');

class AdminController extends WebController implements ICatalog {
	
	public function getRoutes() {
		return Application::$routes->current->routes;
	}
	
	public function getContent() {
		$this->pick('system/catalog');
	}
	
}

?>