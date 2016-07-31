<?php

include_once '../app/views/forms/OrderAdd.php';

class OrderTemplateForm extends OrderAddForm {
	
	public function view($field) {
		if (!$field->isTemplated()) return '';
		return parent::view($field);
	}
	
	protected function removeSendFields(&$fields) {
		//nothing
	}
	
}

?>