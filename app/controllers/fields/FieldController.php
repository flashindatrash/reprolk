<?php

Util::inc('controllers', 'fields/BaseFormController.php');

class FieldController extends BaseFormController {
	
	public $fields;
	
	public function beforeRender() {
		if (!$this->hasPage()) return;
		
		if ($this->editWeight()) {
			$this->addAlert(View::str('success_save'), 'success');
		}
		
		$this->fields = Field::getAll($this->pageName);
		$this->view = 'admin/field/index';
	}
	
	private function editWeight() {
		if (hasGet('id') && hasGet('weight')) {
			return Field::updateWeight(get('id'), int(get('weight')));
		}
		
		return false;
	}
	
}

?>