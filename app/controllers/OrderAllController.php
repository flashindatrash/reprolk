<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields;
	
	public function beforeRender() {
		$this->fields = array('name', 'status', 'date_due');
		
		$this->orders = Order::getAll($this->fields);
		
		$this->addJSfile('order.table');
		$this->addJSparam('view_url', '/order/view?id=');
		$this->addJSparam('edit_url', '/order/edit?id=');
		$this->addJSparam('duplicate_url', '/order/duplicate?id=');
		
	}
	
	public function getContent() {
		$this->pick('order/index');
	}
	
}

?>