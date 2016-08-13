<?php

Util::inc('controllers', 'BaseFieldController.php');

class FieldBindController extends BaseFieldController {
	
	public function beforeRender() {
		if (!$this->hasPage()) return;
		
		if (!hasGet('gid')) {
			$this->view = 'system/group-select';
		} else {
			$this->save();
			
			$this->view = 'admin/field/bind';
			$this->addJSparam('all', reArray(Field::getAll(get('page'), true, false), 'id', 'name'));
			$this->addJSparam('current', reArray(GroupField::getFids(get('gid')), null, 'fid'));
			$this->addJSfile('bind');
		}
	}
	
	public function save() {
		if ($this->formValidate(['gid']) && GroupField::set(post('gid'), hasPost('ids') ? explode(',', post('ids')) : [])) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		return false;
	}
	
}