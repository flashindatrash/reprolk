<?php

Util::inc('forms', 'OrderView.php');

class OrderRepeatForm extends OrderViewForm {
	
	public function loadFields($route, $gid = null) {
		parent::loadFields($route, $gid);
		
		$this->setDefault([
			Order::FIELD_DATE_DUE => (new DateTime('tomorrow'))->format("Y-m-d")
		]);
	}
	
	public function view($field) {
		switch ($field->name) {
			case Order::FIELD_STATUS:
				return '';
			case Order::FIELD_URGENT:
				return View::formOffset($field->name, $field->type, $field->getValue());
			case Order::FIELD_DATE_DUE:
			case Order::FIELD_COMMENT:
				return View::formNormal($field->name, $field->type, $field->getValue());
			default:
				return parent::view($field);
		}
	}
	
}

?>