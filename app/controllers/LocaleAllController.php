<?php

Util::inc('controllers', 'base/WebController.php');

class LocaleAllController extends WebController {
	
	const COUNT_PER_PAGE = 10;
	
	public $route;
	public $locales;
	public $currentPage;
	public $totalPages;
	
	public function beforeRender() {
		//выставим ссылку на роут редактирования
		$this->route = Application::$routes->byName(Route::LOCALE_EDIT);
		
		//определим кол-во страниц
		Util::paging($this->currentPage, $this->totalPages, Locale::getCountTotal(), self::COUNT_PER_PAGE);
		
		//выборка всех локалей
		$this->locales = Locale::getAll(Account::getLang(), self::COUNT_PER_PAGE * $this->currentPage . ', ' . self::COUNT_PER_PAGE);
		
		$this->view = 'admin/locale/all';
	}

}

?>