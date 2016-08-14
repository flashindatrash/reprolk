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
	
}

?>