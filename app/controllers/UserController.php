<?php

class UserController extends BaseController {
	
	public $fields;
	
	public function beforeRender() {
		$this->fields = array('id', 'username', 'email', 'group', 'gid');
	}
	
	public function getContent() {
		$this->pick('user/index');
	}
	
}

?>