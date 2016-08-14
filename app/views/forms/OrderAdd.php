<?php
//форма добавления заказа

include_once '../app/views/forms/Order.php';

class OrderAddForm extends OrderForm {
	
	public function loadFields($route, $gid = null) {
		parent::loadFields($route, $gid);
		
		$this->setDefault([
			Order::FIELD_DATE_DUE => (new DateTime('tomorrow'))->format("Y-m-d")
		]);
		
		$this->setOption([
			Order::FIELD_PID => reArray(GroupPhotopolymer::getAll(), GroupPhotopolymer::FIELD_PID, 'name')
		]);
	}
	
	public function field_pid($field) {
		return View::formNormal($field->name, $field->type, $field->getOption(), true, false, $field->getValue());
	}
	
}

?>