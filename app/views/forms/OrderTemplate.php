<?php

include_once '../app/views/forms/Order.php';

class OrderTemplateForm extends OrderForm {
	
	public function view($field) {
		if (!$field->isTemplated()) return '';
		return parent::view($field);
	}
	
}

?>