<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IRedirect.php');

class BaseOptionController extends WebController implements IRedirect {
	
	public $fid;

	protected function hasField() {
		$currentRoute = Application::$routes->current;
		if ($currentRoute instanceof DataRoute) {
			$this->fid = $currentRoute->data;
		} else if (hasGet('fid')) {
			$this->fid = get('fid');
		} else if (hasPost('fid')) {
			$this->pageName = post('fid');
		}
		
		if (is_null($this->fid) && !Field::hasId($this->fid)) {
			$this->fid = null;
			$this->addAlert(sprintf(View::str('not_found'), View::str('field')), 'danger');
			return false;
		}
		
		return true;
	}
	
	//IRedirect
	public function getRedirect() {	
		return new Redirect(View::str('option_successfuly'), Application::$routes->byName(Route::OPTION_LIST)->path . '/' . strtolower(Field::byId($this->fid)->name));
	}
}