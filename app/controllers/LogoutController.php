<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IRedirect.php');

class LogoutController extends WebController implements IRedirect {
	
	public function beforeRender() {
		Application::$user = null;
		Application::$cookie[Auth::FIELD_KEY] = null;
		$this->setTemplate('empty');
		$this->view = 'system/redirect';
	}
	
	public function getRedirect() {
		return new Redirect(View::str('logout_successfuly'));
	}
	
}

?>