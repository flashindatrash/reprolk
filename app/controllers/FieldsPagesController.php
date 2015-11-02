<?php

class FieldsPagesController extends BaseController {
	
	public $routes;
	
	public function beforeRender() {
		$this->routes = Field::getRoutes();
	}
	
	public function getContent() {
		$this->pick('admin/fields-pages');
	}
	
}

?>