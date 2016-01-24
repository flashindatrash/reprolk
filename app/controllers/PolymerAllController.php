<?php

class PolymerAllController extends BaseController {
	
	public $photopolymers;
	
	public function beforeRender() {
		$this->add();
		
		$this->photopolymers = Photopolymer::getAll();
		$this->view = 'admin/polymer/index';
	}
	
	public function add() {
		if ($this->formValidate(['photopolymer', 'id_1c']) && Photopolymer::add(post('photopolymer'), post('id_1c'))) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		
		return false;
	}
	
}

?>