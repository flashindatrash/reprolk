<?php

include_once '../app/controllers/OrderAddController.php';

class OrderDuplicateController extends OrderAddController {
	
	public function beforeRender() {
		$this->loadOrder();
		if (is_null($this->order)) return;
		parent::beforeRender();
	}
	
	public function createOrderForm() {
		parent::createOrderForm();
		$this->form->setSession(objectToArray($this->order));
	}
	
}

?>