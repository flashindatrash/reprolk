<?php
//форма редактирования заказа

include_once '../app/views/forms/Order.php';

class OrderEditForm extends OrderForm {
	
	public function view($field) {
		switch ($field->name) {
			case Order::FIELD_COMMENT:
				return '';
			default:
				return parent::view($field);
		}
	}
	
}

?>