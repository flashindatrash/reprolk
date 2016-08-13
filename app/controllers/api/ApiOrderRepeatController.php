<?php

Util::inc('controllers', 'api/BaseApiController.php');
Util::inc('controllers', 'OrderRepeatController.php');

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