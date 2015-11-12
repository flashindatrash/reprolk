<?php

include_once '../core/objects/Form.php';

class OrderViewForm extends Form {
	
	public function view($field) {
		switch ($field->type) {
			case 'checkbox':
				return View::formStatic($field->name, View::bool2icon($field->session));
			default:
				return View::formStatic($field->name, $field->session);
		}
	}
	
}

?>