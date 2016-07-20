<?php

include_once '../app/views/forms/OrderView.php';

class OrderRepeatForm extends OrderViewForm {

	public function view($field) {
		switch ($field->name) {
			case Order::FIELD_STATUS:
				return '';
			case Order::FIELD_URGENT:
				return View::formOffset($field->name, $field->type, $field->getValue());
			case Order::FIELD_DATE_DUE:
			case Order::FIELD_COMMENT:
				return View::formNormal($field->name, $field->type, $field->session);
			default:
				return parent::view($field);
		}
	}
	
}

?>