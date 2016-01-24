<?php

include_once '../core/interfaces/IBind.php';
include_once '../app/controllers/BaseFieldController.php';

class FieldBindController extends BaseFieldController implements IBind {
	
	public $fields;
	public $current_fields;
	
	public function beforeRender() {
		if (!$this->hasPage()) return;
		
		if (!hasGet('gid')) {
			$this->view = 'system/group-select';
		} else {
			if ($this->formValidate(['gid'])) {
				$this->save();
			}
			
			$this->fields = reArray(Field::getAll(get('page'), true, false), 'id', 'name');
			$this->current_fields = reArray(GroupField::getFids(get('gid')), 'fid', 'fid');
			$this->view = 'admin/field/bind-fields';
		}
	}
	
	//IBind
	public function save() {
		if (GroupField::set(post('gid'), hasPost('fields') ? post('fields') : [])) {
			$this->addAlert(View::str('success_save'), 'success');
			return true;
		}
		return false;
	}
	
}