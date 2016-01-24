<?php

include_once '../core/objects/OrderFilter.php';

class OrderAllController extends BaseController {
	
	public $orders;
	public $fields_view;
	public $fields_sql;
	public $order_filter;
	public $order_by;
	
	public function beforeRender() {
		$this->order_filter = new OrderFilter();
		$this->order_by = new SQLOrderBy('date_due');
		$this->fields_sql = $this->fields_view = array('title', 'status', 'date_due');
		$this->fields_view[] = 'username';
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		//фильтр
		foreach (OrderFilter::fields() as $field) {
			$this->order_filter->$field = hasGet($field) ? get($field) : null;
		}
		
		//сортировка
		if (hasGet('sort') && in_array(get('sort'), $this->fields_view)) {
			$this->order_by->field = get('sort');
			$this->order_by->by = get('by');
		}
		
		//выборка заказов
		$this->orders = Order::getAll($this->fields_sql, $this->order_filter, Account::getGid(), $this->order_by);
		
		//CSS/JS
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
		$this->addCSSfile('selected.table');
		$this->addJSfile('selected.table');
		$this->addJSparam('view_url', Application::$routes->byName(Route::ORDER_VIEW)->path);
		$this->addJSparam('edit_url', Application::$routes->byName(Route::ORDER_EDIT)->path);
		$this->addJSparam('duplicate_url', Application::$routes->byName(Route::ORDER_DUPLICATE)->path);
	}
	
	public function getContent() {
		$this->pick('order/filter');
		$this->pick('order/index');
	}
	
}

?>