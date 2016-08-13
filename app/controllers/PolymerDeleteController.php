<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class PolymerDeleteController extends BaseController implements IRedirect, IConfirm {
	
	private $photopolymer;
	
	public function beforeRender() {
		$this->photopolymer = hasGet('id') ? Photopolymer::byId(get('id')) : null;
		
		if (is_null($this->photopolymer)) return;
		
		if ($this->formValidate([])) {
			if ($this->photopolymer->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_photopolymer_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('photopolymer_delete_successfuly'), Application::$routes->byName(Route::POLYMER_ALL)->path); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_photopolymer_delete'), $this->photopolymer->name);
	}

}

?>