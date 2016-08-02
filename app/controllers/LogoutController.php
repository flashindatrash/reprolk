<?php

include '../core/interfaces/IRedirect.php';

class LogoutController extends BaseController implements IRedirect {
	
	public function beforeRender() {
		Application::$user = null;
		Application::$cookie[Auth::FIELD_KEY] = null;
	}
	
	public function getContent() {
		$this->pick('system/redirect');
	}
	
	public function getRedirect() {
		return new Redirect(View::str('logout_successfuly'));
	}
	
}

?>