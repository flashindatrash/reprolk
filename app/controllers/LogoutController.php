<?php

include '../core/IRedirect.php';

class LogoutController extends BaseController implements IRedirect {
	
	public function beforeRender() {
		Application::$user = null;
		SystemSession::clear();
	}
	
	public function getContent() {
		$this->pick('system/redirect');
	}
	
	public function getRedirect() {
		return new Redirect($this->str('logout_successfuly'), '/', 300);
	}
	
}

?>