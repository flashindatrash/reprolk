<?php

class PolymerBindController extends BaseController {

	public function beforeRender() {
		if (!hasGet('gid')) {
			$this->view = 'system/group-select';
		} else {
			$this->save();
			
			$this->view = 'admin/polymer/bind';
			$this->addJSparam('all', reArray(Photopolymer::getAll(), 'id', 'name'));
			$this->addJSparam('current', reArray(GroupPhotopolymer::getAll(get('gid')), null, 'pid'));
			$this->addJSfile('bind.group');
		}
	}
	
	public function save() {
		if ($this->formValidate(['ids', 'gid']) && GroupPhotopolymer::set(post('gid'), explode(',', post('ids')))) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		return false;
	}
	
}