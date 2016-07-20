<?php

include_once '../core/interfaces/IRedirect.php';

class LocaleEditController extends BaseController implements IRedirect {
	
	public $key;
	public $locale;
	public $languages;
	
	public function beforeRender() {
		if (!hasGet('key') || validSymbols(get('key'))=='') {
			$this->addAlert(sprintf(View::str('not_found'), View::str('key')), 'danger');
			return;
		}
		
		$this->key = strtoupper(validSymbols(get('key')));
		$this->languages = Locale::getLanguages();
		$this->locale = Locale::byKey($this->key);
		
		if ($this->save()) {
			$this->view = 'system/redirect';
			return;
		}
		
		$this->view = 'admin/locale/edit';
	}
	
	public function getRedirect() {
		return new Redirect(View::str('locale_change_successfuly'), Application::$routes->byName(Route::LOCALE_STATS)->path); 
	}
	
	public function save() {
		if ($this->formValidate($this->languages)) {
			$values = $this->formValues($this->languages);
			return $this->locale->edit($values);
		}
		return false;
	}
	
}

?>