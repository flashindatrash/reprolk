<?php

Util::inc('controllers', 'api/BaseApiController.php');

class ApiOrderAllController extends BaseApiController {
	
	const COUNT_PER_PAGE = 10;
	
	const FIELD_PAGE = 'page';
	
	//форма фильра
	public $filter;
	
	//сортировка
	public $sort_by;
	
	//информация по страницам
	public $currentPage;
	public $totalPages;
	
	//результат выполнения
	public $orders;
	public $total;
	
	const FIELDS_SQL = array(
		Order::FIELD_TITLE, 
		Order::FIELD_RASTER_LINE, 
		Order::FIELD_STATUS, 
		Order::FIELD_DATE_DUE, 
		Order::FIELD_URGENT, 
		Order::FIELD_1C_ID, 
		Order::FIELD_1C_NUMBER,
		Order::FIELD_DATE_CREATED,
		Order::FIELD_DATE_CHANGED
	);
	
	public function execute() {
		//сортировка
		if (hasPost('sort') && in_array(post('sort'), self::FIELDS_SQL)) {
			$this->sort_by = new SQLOrderBy(post('sort'), post('by'));
		} else {
			$this->sort_by = new SQLOrderBy(Order::FIELD_DATE_DUE);
		}
		
		//вторичная сортировка по дате изменения
		$this->sort_by->addOrder(Order::FIELD_DATE_CHANGED, SQLOrderBy::DESC);
		
		//кол-во заказов
		$this->total = Order::getCountTotal($this->filter);
		
		//определим currentPage и totalPages
		Util::paging($this->currentPage, $this->totalPages, $this->total, self::COUNT_PER_PAGE, 'post');
		
		$this->request->setSession([
			'sort' => $this->sort_by->field,
			'by' => $this->sort_by->by,
			'page' => $this->currentPage
		]);
		
		//загрузим заказы
		$this->orders = Order::getAll(self::FIELDS_SQL, $this->filter, $this->sort_by, self::COUNT_PER_PAGE * $this->currentPage . ', ' . self::COUNT_PER_PAGE);
		
		return true;
	}
	
	public function responsed() {
		return array(
			'total' => $this->total,
			'current_page' => $this->currentPage,
			'total_pages' => $this->totalPages,
			'orders' => $this->orders
		);
	}
	
	public function getDefaultRequest() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey(),
			self::FIELD_PAGE => 0
		);
	}
	
	public function createRequestForm() {
		//создадим форму реквеста
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		
		//создадим форму фильтра
		$this->filter = $this->createForm('OrderFilter');
		$this->filter->loadFields(Route::ORDER_ALL);
		
		//смержим две формы
		$form->fields = array_merge($form->fields, $this->filter->fields);
		
		return $form;
	}
	
}

?>