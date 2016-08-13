<?php

//форма выполнения API

include_once '../core/objects/Form.php';

class ApiRequestForm extends Form {
	
	public function addField($name, $type = 'text') {
		$field = new Field();
		$field->name = $name;
		$field->type = $type;
		
		$this->fields[] = $field;
	}
	
}

?>