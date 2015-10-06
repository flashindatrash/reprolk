<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class PhotopolymerDeleteController extends BaseController implements IRedirect, IConfirm {
	
	private $photopolymer;
	private $view;
	
	public function beforeRender() {
		$this->photopolymer = hasGet('id') ? Photopolymer::byId(get('id')) : null;
		
		if (is_null($this->photopolymer)) return;
		
		if ($this->formValidate([])) {
			if ($this->photopolymer->remove()) {
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_photopolymer_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
	public function getRedirect() {
		return new Redirect(View::str('photopolymer_delete_successfuly'), Application::$routes->byName(Route::POLYMERS)->path); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_photopolymer_delete'), $this->photopolymer->name);
	}

}

?>