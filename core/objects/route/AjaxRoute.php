<?php

class AjaxRoute extends Route {
	
	public function __construct($name, $path = '/', $permission = null) {
		parent::__construct($name, $path, $permission, Route::TYPE_HIDDEN);
	}
	
	public function controllerPath() {
		return 'ajax/' . parent::controllerPath();
	}
	
}

?>