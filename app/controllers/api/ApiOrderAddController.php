<?php

Util::inc('controllers', 'api/BaseApiController.php');
Util::inc('controllers', 'OrderAddController.php');

/*
params:
	fields
	
response:
	order
*/

class ApiOrderAddController extends BaseApiController {
	
	private $controller;
	
	public function processingApi() {
		$this->addPostValidator();
		
		$this->controller = new OrderAddController();
		$this->controller->createOrderForm();
		if ($this->controller->add()) {
			$this->success = true;
			$this->response["order"] = $this->controller->order;
		}
		
		$this->mergeAlerts($this->controller);
	}
	
}

?>