<?php

class GroupPhotopolymersController extends BaseController {

	public $photopolymers;
	
	private $is_success;
	
	public function beforeRender() {
		$this->photopolymers = View::convertSelect(Photopolymer::getAll(), 'id', 'name');
		
		//$fields = User::$fields_mandatory;
		$values = $this->formValidate(array ('photopolymers', 'group'));
		
		if (!is_null($values)) {
			$this->is_success = GroupPhotopolymer::set(post('group'), post('photopolymers'));
		}
	}
	
	public function getContent() {
		if ($this->is_success) {
			$this->pick('system/success-save');
		}
		
		$this->pick('admin/group-photopolymers');
	}
	

}