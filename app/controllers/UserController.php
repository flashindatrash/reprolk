<?php

class UserController extends BaseController {
	
	public $fields;
	public $user;
	
	public function beforeRender() {
		$this->fields = array('id', 'username', 'email', 'group', 'gid');
		$this->user = hasGet('id') ? User::byId(get('id')) : Application::$user;
		if (is_null($this->user)) $this->addAlert(View::str('error_user_not_found'), 'warning');
	}
	
	public function getContent() {
		if (!is_null($this->user)) $this->pick('user/index');
	}
	
}

?>