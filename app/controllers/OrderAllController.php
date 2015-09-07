<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $currentGroup;
	
	public function beforeRender() {
		$this->orders = Order::getAll($this->currentGroup);
	}
	
	public function getContent() {
		$this->pick('order/all');
	}
	
}

?>