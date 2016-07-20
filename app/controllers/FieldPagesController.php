<?php

class FieldPagesController extends BaseController {
	
	public $routes;
	
	public function beforeRender() {
		$this->routes = Field::getRoutes();
		$this->view = 'admin/field/index';
	}
	
}

?>