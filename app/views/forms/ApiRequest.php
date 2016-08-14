<?php

//форма выполнения API

include_once '../core/objects/Form.php';

class ApiRequestForm extends Form {
	
	public function addField($name, $type = INPUT_TEXT, $mandatory = false) {
		$field = new Field();
		$field->name = $name;
		$field->type = $type;
		$field->mandatory = $mandatory;
		
		$this->fields[] = $field;
	}
	
	//кастомный рендер полей
	public function field_status($field) {
		return View::formNormal($field->name, $field->type, $field->getOption(), false, false, $field->getValue());
	}
	
	public function field_username($field) {
		return View::formNormal($field->name, $field->type, $field->getOption(), true, false, $field->getValue());
	}
	
	public function field_date_due($field) {
		return View::formNormal($field->name, $field->type, $field->getValue(), false);
	}
	
	public function field_date_created($field) {
		return View::formNormal($field->name, $field->type, $field->getValue(), false);
	}
	
}

?>