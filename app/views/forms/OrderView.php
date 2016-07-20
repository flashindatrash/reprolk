<?php
//форма просмотра заказа

include_once '../app/views/forms/Order.php';

class OrderViewForm extends OrderForm {
	
	public function view($field) {
		switch ($field->type) {
			case 'checkbox':
				return View::formStatic($field->name, View::bool2str($field->session));
		}
		
		switch ($field->name) {
			case Order::FIELD_COMMENT:
				return '';
			case 'id_1c':
			case 'number_1c':
			case 'form_count':
			case 'area':
			case 'angels':
				if (!$field->session) return '';
		}
		
		return View::formStatic($field->name, $field->session);
	}
	
}

?>