<?php

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields_view;
	public $fields_sql;
	public $order_by;
	
	public function beforeRender() {
		$this->order_by = new SQLOrderBy('date_due');
		$this->fields_sql = $this->fields_view = array('title', 'status', 'date_due');
		$this->fields_view[] = 'username';
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		//установка сортировки
		if (hasGet('sort') && in_array(get('sort'), $this->fields_view)) {
			$this->order_by->field = get('sort');
			$this->order_by->by = get('by');
		}
		
		//выборка заказов
		$this->orders = Order::getAll($this->fields_sql, Account::getGid(), $this->order_by);
		
		$this->addJSfile('order.table');
		$this->addJSparam('view_url', Application::$routes->byName('OrderView')->path);
		$this->addJSparam('edit_url', Application::$routes->byName('OrderEdit')->path);
		$this->addJSparam('duplicate_url', Application::$routes->byName('OrderDuplicate')->path);
	}
	
	public function getContent() {
		$this->pick('order/filter');
		$this->pick('order/index');
	}
	
}

?>