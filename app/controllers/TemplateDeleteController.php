<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class TemplateDeleteController extends BaseController implements IRedirect, IConfirm {
	
	private $template;
	
	public function beforeRender() {
		$this->template = hasGet('id') ? Template::byId(get('id')) : null;
		
		if (is_null($this->template)) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('template')), 'danger');
			return;
		}
		
		if ($this->formValidate([])) {
			if ($this->template->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_template_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('template_delete_successfuly'), Application::$routes->byName(Route::TEMPLATE_VIEW)->path); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_template_delete'), $this->template->name);
	}

}

?>