<?php

include_once '../app/controllers/BaseFieldController.php';

class FieldBindController extends BaseFieldController {
	
	public $gids;
	public $fields;
	public $current_fields;
	
	public function beforeRender() {
		if (!$this->hasPage()) return;
		
		if (!hasGet('gid')) {
			$this->gids = View::convertSelect(User::getAllGroups(), 'gid', 'username');
			$this->view = 'admin/field/bind-group';
		} else {
			if ($this->formValidate(['gid']) && GroupField::set(post('gid'), hasPost('fields') ? post('fields') : [])) {
				$this->addAlert(View::str('success_save'), 'success');
			}
			
			$this->fields = View::convertSelect(Field::getAll(get('page'), true, false), 'id', 'name');
			$this->current_fields = View::convertSelect(GroupField::getFids(Account::getGid()), 'fid', 'fid');
			$this->view = 'admin/field/bind-fields';
		}
	}
	
	
}