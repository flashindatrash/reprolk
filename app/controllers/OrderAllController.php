<?php

include_once '../core/objects/OrderFilter.php';

class OrderAllController extends BaseController {
	
	const COUNT_PER_PAGE = 10;
	const FIELDS_SQL = array(Order::FIELD_TITLE, Order::FIELD_RASTER_LINE, Order::FIELD_STATUS, Order::FIELD_DATE_DUE, Order::FIELD_URGENT);
	
	public $orders;
	public $fields_view;
	public $order_filter;
	public $order_by;
	public $currentPage;
	public $totalPages;
	
	public function beforeRender() {
		$this->order_filter = new OrderFilter();
		$this->fields_view = array(Order::FIELD_TITLE, 'photopolymer_name', Order::FIELD_RASTER_LINE, Order::FIELD_STATUS, Order::FIELD_DATE_DUE, 'username');
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		//фильтр из GET'a
		foreach (OrderFilter::fields() as $field) {
			$this->order_filter->$field = hasGet($field) ? get($field) : null;
		}
		$this->applyFillter();
		
		//сортировка
		if (hasGet('sort') && in_array(get('sort'), $this->fields_view)) {
			$this->order_by = new SQLOrderBy(get('sort'), get('by'));
		} else {
			$this->order_by = new SQLOrderBy(Order::FIELD_DATE_DUE);
		}
		
		//вторичная сортировка по дате изменения
		$this->order_by->addOrder('date_changed', SQLOrderBy::DESC);
		
		//определим кол-во страниц
		$this->applyPaginator($this->currentPage, $this->totalPages, Order::getCountTotal($this->order_filter, Account::getGid()), self::COUNT_PER_PAGE);
		
		//выборка заказов
		$this->orders = self::loadOrders(self::FIELDS_SQL, $this->order_filter, $this->order_by, self::COUNT_PER_PAGE * $this->currentPage . ', ' . self::COUNT_PER_PAGE);
		
		$this->include_datetimepicker();
		$this->include_other();
	}
	
	public static function loadOrders($fields, $filter, $by, $range) {
		return Order::getAll($fields, $filter, Account::getGid(), $by, $range);
	}
	
	public function getContent() {
		$this->pick('order/filter');
		$this->pick('order/index');
	}
	
	protected function applyFillter() {
		$this->order_filter->statuses = $this->order_filter->getDefaultStatuses();
		if (is_null($this->order_filter->status)) {
			$this->order_filter->status = $this->order_filter->statuses;
		}
	}
	
	protected function include_other() {
		$this->addCSSfile('selected.table');
		$this->addJSfile('selected.table');
		$this->addJSparam('view_url', Application::$routes->byName(Route::ORDER_VIEW)->path);
		$this->addJSparam('edit_url', Application::$routes->byName(Route::ORDER_EDIT)->path);
		$this->addJSparam('duplicate_url', Application::$routes->byName(Route::ORDER_DUPLICATE)->path);
		$this->addJSparam('repeat_url', Application::$routes->byName(Route::ORDER_REPEAT)->path);
		$this->addJSparam('delete_url', Application::$routes->byName(Route::ORDER_DELETE)->path);
	}
	
}

?>