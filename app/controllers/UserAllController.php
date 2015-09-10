<?php

class UserAllController extends BaseController {
	
	public $users;
	public $groups;
	public $currentGroup;
	
	public function beforeRender() {
		$this->groups = UserAccess::$permissions[UserAccess::AUTH];
		$this->currentGroup = in_array(get('group'), $this->groups) ? get('group') : null;
		$this->users = User::getAll($this->currentGroup);
	}
	
	public function getContent() {
		$this->pick('user/all');
	}
	
}

?>