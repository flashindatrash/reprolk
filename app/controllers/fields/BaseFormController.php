<?php

Util::inc('controllers', 'base/WebController.php');

class BaseFormController extends WebController {
	
	public $pageName;

	protected function hasPage() {
		$currentRoute = Application::$routes->current;
		if ($currentRoute instanceof DataRoute) {
			$this->pageName = $currentRoute->data;
		} else if (hasGet('page')) {
			$this->pageName = get('page');
		} else if (hasPost('page')) {
			$this->pageName = post('page');
		}
		
		if (is_null($this->pageName) && is_null(Application::$routes->byName($this->pageName))) {
			$this->pageName = null;
			$this->addAlert(sprintf(View::str('not_found'), View::str('form')), 'danger');
			return false;
		}
		
		return true;
	}
	
}