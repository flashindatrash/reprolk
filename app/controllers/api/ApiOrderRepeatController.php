<?php

include_once '../app/controllers/api/BaseApiController.php';
include_once '../app/controllers/OrderRepeatController.php';

/*
params:
	fields
	
response:
	order
*/

class ApiOrderRepeatController extends BaseApiController {
	
	private $controller;
	
	public function processingApi() {
		$this->controller = new OrderRepeatController();
		
		//загрузим дублированный заказ
		if ($this->controller->loadDuplicateOrder()) {
			$this->addPostValidator();
			$this->controller->createOrderForm();
			if ($this->controller->add()) {
				$this->success = true;
				$this->response["order"] = $this->controller->order;
			}
		}
		
		$this->mergeAlerts($this->controller);
	}
	
}

?>