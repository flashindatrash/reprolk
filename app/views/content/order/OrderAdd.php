<?php

class OrderAddPage extends Page {
	
	public $photopolymers;
	public $commented;
	
	public function field_pid($field) {
		return View::formNormal($field->name, $field->type, $this->photopolymers, true, false, $field->session);
	}
	
	public function field_files($field) {
		return View::formOffset($field->name, $field->type);
	}
	
	public function field_comment($field) {
		return $this->commented ? View::formOffset($field->name, $field->type) : '';
	}
	
}

?>