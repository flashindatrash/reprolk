<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields;
	
	public function beforeRender() {
		$this->fields = array('title', 'status', 'date_due');
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		$this->orders = Order::getAll($this->fields, Account::getGid());
		
		$this->addJSfile('order.table');
		$this->addJSparam('view_url', Application::$routes->byName('OrderView')->path);
		$this->addJSparam('edit_url', Application::$routes->byName('OrderEdit')->path);
		$this->addJSparam('duplicate_url', Application::$routes->byName('OrderDuplicate')->path);
	}
	
	public function getContent() {
		$this->pick('order/index');
	}
	
}

?>