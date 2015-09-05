<?php

class UserAllController extends BaseController {
	
	public $users;
	
	public function beforeRender() {
		$this->users = User::getAll();
	}
	
	public function getContent() {
		$this->pick('user/all');
	}
	
}

?>