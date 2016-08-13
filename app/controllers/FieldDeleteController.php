<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IConfirm.php');
Util::inc('interfaces', 'IRedirect.php');

class FieldDeleteController extends WebController implements IRedirect, IConfirm {
	
	private $field;
	
	public function beforeRender() {
		$this->field = hasGet('id') ? Field::byId(get('id')) : null;
		
		if (is_null($this->field)) return;
		
		if (!$this->field->canDelete()) {
			$this->addAlert(View::str('warning_field_delete'), 'warning');
			return;
		}
		
		if ($this->formValidate([])) {
			if ($this->field->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(sprintf(View::str('error_field_delete'), $this->field->name), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('field_delete_successfuly'), Application::$routes->byName(Route::FIELD_PAGE)->path . '?page=' . $this->field->route); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_field_delete'), $this->field->name);
	}

}

?>