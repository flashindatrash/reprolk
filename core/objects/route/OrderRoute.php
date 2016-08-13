<?php

class OrderRoute extends Route {
	
	public function controllerPath() {
		return 'order/' . parent::controllerPath();
	}
	
}

?>