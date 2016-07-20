<?php

include_once '../app/controllers/OrderAddController.php';

class OrderDuplicateController extends OrderAddController {
	
	public function beforeRender() {
		$this->loadOrder();
		if (is_null($this->order)) return;
		
		if (!$this->order->canDuplicate()) {
			$this->addAlert(View::str('error_order_cannot_duplicate'), 'danger');
			return;
		}
		
		parent::beforeRender();
	}
	
	public function createOrderForm() {
		unset($this->order->date_due); //очистим date_due, т.к. дальше форма должна создасться с завтрешним днем
		
		parent::createOrderForm();
		
		$this->form->setSession(objectToArray($this->order));
	}
	
}

?>