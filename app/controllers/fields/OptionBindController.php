<?php

Util::inc('controllers', 'fields/BaseOptionController.php');

class OptionBindController extends BaseOptionController {

	public function beforeRender() {
		if (!$this->hasField()) return;
		
		if (!hasGet('gid')) {
			$this->view = 'system/group-select';
		} else {
			$this->save();
			
			$this->view = 'admin/option/bind';
			$this->addJSparam('all', reArray(Option::getAll($this->fid), 'id', 'name'));
			$this->addJSparam('current', reArray(GroupOption::getAll($this->fid, get('gid')), null, GroupOption::FIELD_OID));
			$this->addJSfile('bind');
		}
	}
	
	public function save() {
		if ($this->formValidate(['ids', 'gid']) && GroupOption::set($this->fid, post('gid'), explode(',', post('ids')))) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		return false;
	}
	
}