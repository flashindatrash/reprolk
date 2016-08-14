<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('controllers', 'api/ApiOrderAllController.php');

class OrderAllController extends WebController {
	
	const COUNT_PER_PAGE = 10;
	
	public $api;
	public $fields_view = array(Order::FIELD_TITLE, 'photopolymer_name', Order::FIELD_RASTER_LINE, Order::FIELD_STATUS, Order::FIELD_DATE_DUE, 'username');
	
	public function beforeRender() {
		//примержим все в POST, т.к. API работает с постом
		$_POST = array_merge($_GET, $_POST);
		
		//создадим API метод
		$this->api = new ApiOrderAllController();
		
		//выполним метод
		if ($this->api->checkRequest()) {
			$this->api->execute();
			
			$this->include_datetimepicker();
			$this->include_other();
		}
	}
	
	public function getContent() {
		$this->pick('order/filter');
		$this->pick('order/index');
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