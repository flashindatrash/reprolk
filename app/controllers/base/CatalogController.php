<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'ICatalog.php');

class CatalogController extends WebController implements ICatalog {
	
	public function beforeRender() {
		$this->view = 'system/catalog';
	}

	public function getRoutes() {
		return Application::$routes->current->routes;
	}
	
}

?>