<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class FieldDeleteController extends BaseController implements IRedirect, IConfirm {
	
	private $field;
	private $view;
	
	public function beforeRender() {
		$this->field = hasGet('id') ? Field::byId(get('id')) : null;
		
		if (is_null($this->field)) return;
		
		if (!$this->field->canDelete()) {
			$this->addAlert(View::str('warning_field_delete'), 'warning');
			return;
		}
		
		if ($this->formValidate([])) {
			if ($this->field->remove()) {
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(sprintf(View::str('error_field_delete'), $this->field->name), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
	public function getRedirect() {
		return new Redirect(View::str('field_delete_successfuly'), Application::$routes->byName(Route::FIELD_EDIT)->path . '?page=' . $this->field->route); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_field_delete'), $this->field->name);
	}

}

?>