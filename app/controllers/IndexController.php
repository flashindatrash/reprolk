<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'ICatalog.php');

class IndexController extends WebController implements ICatalog {
	
	public function getRoutes() {
		return Application::$routes->all;
	}
	
	public function getContent() {
		$this->pick('system/catalog');
	}
	
}

?>