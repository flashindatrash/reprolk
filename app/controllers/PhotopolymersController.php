<?php

class PhotopolymersController extends BaseController {
	
	public $photopolymers;
	
	public function beforeRender() {
		$this->add();
		
		$this->photopolymers = Photopolymer::getAll();
	}
	
	public function getContent() {
		$this->pick('admin/photopolymers');
	}
	
	public function add() {
		if ($this->formValidate(['photopolymer']) && Photopolymer::add(post('photopolymer'))) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		
		return false;
	}
	
}

?>