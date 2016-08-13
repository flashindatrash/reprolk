<?php

Util::inc('controllers', 'order/OrderAddController.php');

class OrderDuplicateController extends OrderAddController {
	
	public function beforeRender() {
		$this->loadDuplicateOrder();
		parent::beforeRender();
	}
	
	public function loadDuplicateOrder() {
		$this->loadOrder();
		if (is_null($this->order)) return false;
		
		if (!$this->order->canDuplicate()) {
			$this->addAlert(View::str('error_order_cannot_duplicate'), 'danger');
			return false;
		}
		return true;
	}
	
	public function createOrderForm() {
		unset($this->order->date_due); //очистим date_due, т.к. дальше форма должна создасться с завтрешним днем
		
		parent::createOrderForm();
		
		$this->form->setSession(objectToArray($this->order));
	}
	
}

?>