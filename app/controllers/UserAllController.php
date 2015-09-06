<?php

class UserAllController extends BaseController {
	
	public $users;
	public $currentGroup;
	
	public function beforeRender() {
		$this->currentGroup = in_array(get('group'), UserAccess::$permissions[UserAccess::ALL]) ? get('group') : null;
		$this->users = User::getAll($this->currentGroup);
	}
	
	public function getContent() {
		$this->pick('user/all');
	}
	
}

?>