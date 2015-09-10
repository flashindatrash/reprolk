<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields;
	
	public function beforeRender() {
		$this->fields = array('id', 'jobname', 'jobid1c');
		$this->orders = Order::getAll($this->fields);
	}
	
	public function getContent() {
		$this->pick('order/index');
	}
	
}

?>