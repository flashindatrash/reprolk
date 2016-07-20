<?php
//базовая форма добавления

include_once '../core/objects/Form.php';

class OrderForm extends Form {
	
	public function fieldsAll() {
		$fields = parent::fieldsAll();
		$this->removeSendFields($fields);
		return $fields;
	}
	
	public function field_comment($field) {
		return UserAccess::check(UserAccess::COMMENT_ADD) ? $this->view($field) : '';
	}
	
	protected function removeSendFields(&$fields) {
		removeArrayItem(Order::FIELD_COMMENT, $fields);
	}
	
}

?>