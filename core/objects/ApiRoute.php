<?php

class ApiRoute extends Route {
	
	public function controllerPath() {
		return 'api/' . parent::controllerPath();
	}
	
}

?>