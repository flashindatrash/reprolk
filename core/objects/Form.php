<?php

class Form {
	
	public $fields = [];
	
	public function loadFields($route, $gid = null) {
		if (is_null($gid)) $gid = Account::getGid();
		$this->fields = array_merge(Field::getAll($route, false), GroupField::getAll($route, $gid));
		
		//отсортируем смерженые поля по весу
		uasort($this->fields, array($this, 'sortWeight'));
		$this->fields = reArray($this->fields, null, null);
		
		//заполним постом
		$this->parsePost();
	}
	
	public function fieldsMandatory() {
		$mandatory = array();
		foreach($this->fields as $field) {
			if ($field->isMandatory()) {
				$mandatory[] = $field->name;
			}
		}
		return $mandatory;
	}
	
	public function fieldsAll() {
		$all = array();
		foreach($this->fields as $field) {
			if (!$field->canUse()) continue;
			$all[] = $field->name;
		}
		return $all;
	}
	
	//возвращает ид-шники для заполнения в базу, при изменении темплейта
	public function fieldsAllID() {
		$all = array();
		foreach($this->fields as $field) {
			if (!$field->canUse()) continue;
			$all[] = $field->id;
		}
		return $all;
	}
	
	public function render() {
		$html = View::formValidate();
		foreach($this->fields as $field) {
			$method = 'field_' . $field->name;
			if (method_exists($this, $method)) {
				$html .= $this->{$method}($field);
			} else {
				$html .= hook(HOOK_FORM_RENDER, $this->view($field), $field);
			}
		}
		return $html;
	}
	
	public function view($field) {
		switch ($field->type) {
			case 'hidden':
				return View::input($field->name, $field->type);
			case 'select':
			case 'multiple':
				return View::formNormal($field->name, $field->type, $field->getOption(), false, true, $field->getValue());
			case 'checkbox':
				return View::formOffset($field->name, $field->type, $field->getValue());
			case 'file':
			case 'files':
			case 'submit':
				return View::formOffset($field->name, $field->type);
			default:
				return View::formNormal($field->name, $field->type, $field->getValue());
		}
	}
	
	public function setSession($array) {
		foreach ($this->fields as $field) {
			if (array_key_exists($field->name, $array)) {
				$field->session = $array[$field->name];
			}
		}
	}
	
	private function parsePost() {
		foreach ($this->fields as $field) {
			if ($field->type=='checkbox') {
				$_POST[$field->name] = toBool(post($field->name));
			}
			
			$hook = hook(HOOK_FORM_PARSEPOST, null, $field->name);
			
			if ($hook!==null) {
				$field->session = $hook;
			} else if (hasPost($field->name)) {
				$field->session = post($field->name);
			}
		}
	}
	
	private function sortWeight($a, $b) {
		return ($a->weight == $b->weight) ? 0 : ($a->weight < $b->weight) ? -1 : 1;
	}
	
}

?>