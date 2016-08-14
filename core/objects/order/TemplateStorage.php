<?php

class TemplateStorage {
	
	const FIELD_TEMPLATE = 'template';
	
	private $templates;
	private $fields;
	private $current;
	
	public function __construct() {
		switch(Application::$routes->current->name) {
			case Route::ORDER_ADD:
				$this->templates = Template::getAll(Account::getGid());
				$this->current = hasGet(TemplateStorage::FIELD_TEMPLATE) ? get(TemplateStorage::FIELD_TEMPLATE) : 0;
				$this->fields = $this->current!=0 ? GroupTemplate::getAll($this->current) : null;
			break;
		}
	}
	
	public function hasActive() {
		return !is_null($this->fields);
	}
	
	public function getFieldsValue() {
		if (!$this->hasActive()) return [];
		return reArray($this->fields, 'name', 'value');
	}
	
	public function generateBreadcrump() {
		if (!$this->hasTemplates()) {
			return '';
		}
		
		$li = array();
		$li[] = View::rightBreadcrumpLink(View::link(Route::ORDER_ADD, View::str('new_form')), $this->equal(0));
		$li[] = View::rightBreadcrumpLink(null); //separator
		foreach ($this->templates as $template) {
			$li[] = View::rightBreadcrumpLink($this->linkToTemplate($template), $this->equal($template->id));
		}
		
		return View::rightBreadcrumpDropdown(View::str('select_template'), $li);
	}
	
	private function hasTemplates() {
		return !is_null($this->templates) && count($this->templates)>0;
	}
	
	private function linkToTemplate($template) {
		return View::link(Route::ORDER_ADD, $template->name, TemplateStorage::FIELD_TEMPLATE . '=' . $template->id);
	}
	
	private function equal($id) {
		return $this->current==$id;
	}
	
}

?>