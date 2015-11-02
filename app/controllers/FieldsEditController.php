<?php

class FieldsEditController extends BaseController {
	
	public $page;
	public $fields;
	
	public function beforeRender() {
		if (!hasGet('page')) {
			$this->notfound();
			return;
		}
		
		$this->page = Application::$routes->byName(get('page'));
		if (is_null($this->page)) {
			$this->notfound();
			return;
		}
		
		$this->fields = Field::getAll($this->page->name);
	}
	
	public function getContent() {
		if (!is_null($this->page)) $this->pick('admin/fields-edit');
	}
	
	private function notfound() {
		$this->addAlert(View::str('error_page_fieldset_not_found'), 'danger');
	}
	
}

?>