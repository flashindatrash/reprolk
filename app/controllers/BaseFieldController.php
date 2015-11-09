<?php

class BaseFieldController extends BaseController {
	
	protected function hasPage() {
		if (!hasGet('page') || is_null(Application::$routes->byName(get('page')))) {
			$this->notfound();
			return false;
		}
		
		return true;
	}
	
	protected function notfound() {
		$this->addAlert(View::str('error_page_fieldset_not_found'), 'danger');
	}
	
}