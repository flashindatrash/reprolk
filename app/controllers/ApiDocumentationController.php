<?php

include '../core/interfaces/ICatalog.php';

class ApiDocumentationController extends BaseController implements ICatalog {
	
	public function getRoutes() {
		$current = Application::$routes->current;
		$routes = Application::$routes->findByClass('ApiRoute');
		$ret = array();
		foreach ($routes as $route) {
			$r = clone $route;
			$r->type = Route::TYPE_NORMAL;
			
			$ret[] = $r;
		}
		return $ret;
	}
	
	public function getContent() {
		$this->pick('system/catalog');
	}
	
}

?>