<?php
//форма редактирования заказа

include_once '../app/views/forms/OrderAdd.php';

class OrderEditForm extends OrderAddForm {
	
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