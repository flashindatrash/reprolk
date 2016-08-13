<?php

Util::inc('controllers', 'base/WebController.php');

class FieldPagesController extends WebController {
	
	public $routes;
	
	public function beforeRender() {
		$this->routes = Field::getRoutes();
		$this->view = 'admin/field/index';
	}
	
}

?>