<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields;
	
	public function beforeRender() {
		$this->fields = Order::$fields_table_view;
		$this->orders = Order::getAll(Order::$fields_table_select);
		
		$this->addJS('order.table');
		$this->addJSparams('view_url', '/order/view?id=');
	}
	
	public function getContent() {
		$this->pick('order/index');
	}
	
}

?>