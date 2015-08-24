<?php

class LogoutController extends BaseController {
	
	public function getContent() {
		Application::$user = $_SESSION['user_id'] = null;
		$this->pick('system/redirect');
	}
	
	public function getMessage() {
		return $this->str('LOGOUT_SUCCESSFULY');
	}
	
	public function getURL() {
		return '/';
	}
	
}

?>