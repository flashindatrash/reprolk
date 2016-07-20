<?php

include_once '../app/views/forms/OrderView.php';

class OrderRepeatForm extends OrderViewForm {

	public function view($field) {
		switch ($field->name) {
			case 'status':
				return '';
			case 'urgent':
				return View::formOffset($field->name, $field->type, $field->getValue());
			case 'date_due':
				return View::formNormal($field->name, $field->type, $field->session);
			default:
				return parent::view($field);
		}
	}
	
}

?>