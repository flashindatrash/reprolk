<?php

class Page {
	
	protected $fields = [];
	protected $template;
	
	public function __construct() {
		$this->loadFields();
		$this->parseFields();
	}
	
	public function getContent() {
		include_once $this->template;
	}
	
	public function fieldsMandatory() {
		$mandatory = array();
		foreach($this->fields as $field) {
			if ($field->isSystem()) continue;
			if ($field->isMandatory()) {
				$mandatory[] = $field->name;
			}
		}
		return $mandatory;
	}
	
	public function fieldsAll() {
		$all = array();
		foreach($this->fields as $field) {
			if ($field->isSystem()) continue;
			$all[] = $field->name;
		}
		return $all;
	}
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function render() {
		$html = '';
		foreach($this->fields as $field) {
			$method = 'field_' . $field->name;
			if (method_exists($this, $method)) {
				$html .= $this->{$method}($field);
			} else {
				$html .= $this->view($field);
			}
		}
		return $html;
	}
	
	public function view($field) {
		switch ($field->type) {
			case 'hidden':
				return View::input($field->name, $field->type, $field->value);
			case 'select':
			case 'multiple':
				return View::formNormal($field->name, $field->type, $field->value, false, true, $field->session);
			case 'file':
			case 'files':
			case 'submit':
				return View::formOffset($field->name, $field->type, $field->value);
			default:
				return View::formNormal($field->name, $field->type, $field->session);
		}
	}
	
	private function parseFields() {
		foreach($this->fields as $field) {
			if (!hasPost($field->name)) continue;
			switch ($field->type) {
				case 'checkbox':
					$_POST[$field->name] = checkbox2bool(post($field->name));
				break;
			}
			$field->session = post($field->name);
		}
	}
	
	private function loadFields() {
		if (!is_null(Application::$routes->current)) $this->fields = Field::getAll(Application::$routes->current->name);
	}
	
}

?>