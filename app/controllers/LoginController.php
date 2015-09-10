<?php

include '../core/IRedirect.php';

class LoginController extends BaseController implements IRedirect {
	
	public $hasInput;
	public $isLoggined;
	
	public function beforeRender() {
		$this->hasInput = hasPost('email') && hasPost('password');
		if ($this->hasInput) $this->login(post('email'), post('password'));
		
		$this->isLoggined = !is_null(Application::$user);
	}
	
	public function getContent() {
		$this->pick($this->isLoggined ? 'system/redirect' : 'system/login');
	}
	
	private function login($email, $password) {
		Application::$user = User::login($email, $password);
		if (!is_null(Application::$user)) $_SESSION['user_id'] = Application::$user->id;
		else $this->addError($this->str('error_sign_in'));
	}
	
	public function getRedirect() {
		$url = post('_url');
		if ($url=='') $url = '/user';
		return new Redirect($this->str('sign_successfuly'), $url, 1500);
	}
	
}

?>