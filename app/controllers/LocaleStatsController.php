<?php

class LocaleStatsController extends BaseController {
	
	public $total;
	public $countByLang;
	public $languages;
	public $route;
	
	public function beforeRender() {
		//найдем все языки
		$this->languages = Locale::getLanguages();
		
		//выставим ссылку на роут редактирования
		$this->route = Application::$routes->byName(Route::LOCALE_EDIT);
		
		//всего локалей
		$this->total = Locale::getCountTotal();
		
		//хэшик заполненых локалей
		$this->countByLang = array();
		foreach ($this->languages as $lang) {
			$this->countByLang[$lang] = Locale::getCountTotal($lang);
		}
		
		$this->view = 'admin/locale/stats';
	}

}

?>