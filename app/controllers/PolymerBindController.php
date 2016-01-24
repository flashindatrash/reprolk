<?php

class PolymerBindController extends BaseController {

	public $photopolymers;
	public $current_photopolymers;
	
	public function beforeRender() {
		if (!hasGet('gid')) {
			$this->view = 'system/group-select';
		} else {
			$this->save();
			
			$this->photopolymers = reArray(Photopolymer::getAll(), 'id', 'name');
			$this->current_photopolymers = reArray(GroupPhotopolymer::getAll(get('gid')), 'pid', 'pid');
			
			$this->view = 'admin/polymer/bind';
		}
	}
	
	private function save() {
		if ($this->formValidate(['photopolymers', 'gid']) && GroupPhotopolymer::set(post('gid'), post('photopolymers'))) {
			$this->addAlert(View::str('success_save'), 'success');
		}
	}
	
}