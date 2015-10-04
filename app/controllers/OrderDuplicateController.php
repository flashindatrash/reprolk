<?php

include '../app/controllers/BaseOrderController.php';

class OrderDuplicateController extends BaseOrderController {
	
	public function beforeRender() {
		$this->loadOrder();
	}
	
	public function getContent() {
		if ($this->order) print_r($this->order);
	}
	
}

?>