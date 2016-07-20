<?php

class BaseFieldController extends BaseController {
	
	protected function hasPage() {
		if (!hasGet('page') || is_null($this->getRoute())) {
			$this->notfound();
			return false;
		}
		
		return true;
	}
	
	protected function getRoute() {
		return Application::$routes->byName(get('page'));
	}
	
	protected function notfound() {
		$this->addAlert(sprintf(View::str('not_found'), View::str('fieldset')), 'danger');
	}
	
}