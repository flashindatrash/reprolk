<?php

include_once '../app/controllers/api/BaseApiController.php';
include_once '../app/controllers/OrderAddController.php';

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
		
		$this->alerts = array_merge($this->alerts, $this->controller->alerts);
	}
	
}

?>