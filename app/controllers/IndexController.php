<?php

include '../core/ICatalog.php';

class IndexController extends BaseController implements ICatalog {
	
	public function getRoutes() {
		return Application::$routes->all;
	}
	
	public function getContent() {
		$this->pick('system/catalog');
	}
	
}

?>