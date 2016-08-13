<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IRedirect.php');

class LanguageSetController extends WebController implements IRedirect {
	
	public function beforeRender() {
		if (hasGet('l') && in_array(get('l'), Locale::getLanguages())) {
			Account::setLang(get('l'));
		}
		$this->setTemplate('empty');
		$this->view = 'system/redirect';
	}
	
	public function getRedirect() {
		return new Redirect(View::str('language_change_successfuly'), Application::$routes->byName(Route::INDEX)->path); 
	}

}

?>