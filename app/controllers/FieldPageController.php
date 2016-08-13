<?php

Util::inc('controllers', 'BaseFieldController.php');

class FieldPageController extends BaseFieldController {
	
	public $page;
	public $fields;
	
	public function beforeRender() {
		if (!$this->hasPage()) return;
		
		$this->page = $this->getRoute();
		
		if ($this->editWeight()) {
			$this->addAlert(View::str('success_save'), 'success');
		}
		
		$this->fields = Field::getAll($this->page->name);
		$this->view = 'admin/field/page';
	}
	
	private function editWeight() {
		if (hasGet('id') && hasGet('weight')) {
			return Field::updateWeight(get('id'), int(get('weight')));
		}
		
		return false;
	}
	
}

?>