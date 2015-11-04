<?php

include_once '../core/objects/Form.php';

class OrderForm extends Form {

	public function field_pid($field) {
		return View::formNormal($field->name, $field->type, $field->value, true, false, $field->session);
	}
	
}