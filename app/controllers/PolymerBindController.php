<?php

class PolymerBindController extends BaseController {

	public $photopolymers;
	
	private $is_success;
	
	public function beforeRender() {
		$this->photopolymers = View::convertSelect(Photopolymer::getAll(), 'id', 'name');
		
		if ($this->formValidate(['photopolymers', 'group']) && GroupPhotopolymer::set(post('group'), post('photopolymers'))) {
			$this->addAlert(View::str('success_save'), 'success');
		}
	}
	
	public function getContent() {
		$this->pick('admin/polymer/bind');
	}
	

}