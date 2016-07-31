<?php
//форма добавления заказа

include_once '../app/views/forms/Order.php';

class OrderAddForm extends OrderForm {
	
	public function field_pid($field) {
		return View::formNormal($field->name, $field->type, reArray(GroupPhotopolymer::getAll(), GroupPhotopolymer::FIELD_PID, 'name'), true, false, $field->getValue());
	}
	
}

?>