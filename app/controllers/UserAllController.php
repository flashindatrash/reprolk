<?php

class UserAllController extends BaseController {
	
	public $users;
	public $groups;
	public $fields;
	public $currentGroup;
	
	public function beforeRender() {
		$this->fields = array('id', 'username', 'email', 'group', 'gid');
		$this->groups = UserAccess::$permissions[UserAccess::AUTH];
		$this->currentGroup = in_array(get('group'), $this->groups) ? get('group') : null;
		$this->groups[] = 'all';
		$this->users = User::getAll($this->fields, $this->currentGroup);
		$this->view = 'admin/user/all';
	}
	
}

?>