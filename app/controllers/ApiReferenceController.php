<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'ICatalog.php');

class ApiReferenceController extends WebController implements ICatalog {
	
	public function beforeRender() {
		$this->view = 'system/catalog';
	}
	
	public function getRoutes() {
		$routes = Application::$routes->findByClass('ApiRoute');
		$ret = array();
		foreach ($routes as $route) {
			$r = clone $route;
			$r->type = Route::TYPE_NORMAL;
			
			$ret[] = $r;
		}
		return $ret;
	}
	
}

?>