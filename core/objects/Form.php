<?php

Util::inc('objects', 'Input.php');

class Form {
	
	public $fields = [];
	public $gid;
	
	public function loadFields($route, $gid = null) {
		if (is_null($gid) && !Account::isAdmin()) $gid = Account::getGid();
		$this->gid = $gid;
		$this->fields = array_merge(Field::getAll($route, false), GroupField::getAll($route, $this->gid));
		
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
	
	//field по имени
	public function fieldByName($name) {
		foreach($this->fields as $field) {
			if ($field->name==$name) return $field;
		}
		return null;
	}
	
	public function valueByName($name) {
		$field = $this->fieldByName($name);
		return !is_null($field) ? $field->getValue() : null;
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
			case INPUT_HIDDEN:
				return View::input($field->name, $field->type, $field->getValue());
			case INPUT_SELECT:
			case INPUT_MULTIPLE:
				return View::formNormal($field->name, $field->type, $field->getOption(), false, true, $field->getValue());
			case INPUT_CHECKBOX:
				return View::formOffset($field->name, $field->type, $field->getValue());
			case INPUT_FILE:
			case INPUT_FILES:
			case INPUT_SUBMIT:
				return View::formOffset($field->name, $field->type);
			case INPUT_DATE:
				return View::formNormal($field->name, $field->type, $field->getValue(), true);
			default:
				return View::formNormal($field->name, $field->type, $field->getValue());
		}
	}
	
	public function setDefault($array) {
		foreach ($this->fields as $field) {
			if (array_key_exists($field->name, $array)) {
				$field->default = $array[$field->name];
			}
		}
	}
	
	public function setOption($array) {
		foreach ($this->fields as $field) {
			if (array_key_exists($field->name, $array)) {
				$field->option = $array[$field->name];
			}
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
			if ($field->type==INPUT_CHECKBOX) {
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