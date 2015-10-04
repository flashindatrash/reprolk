<?php

include_once '../core/interfaces/IRedirect.php';

class LoginController extends BaseController implements IRedirect {
	
	public function beforeRender() {
		if ($this->formValidate(['email', 'password'])) {
			$this->login(post('email'), post('password'));
		}
	}
	
	public function getContent() {
		$this->pick(Account::isLogined() ? 'system/redirect' : 'system/login');
	}
	
	private function login($email, $password) {
		Application::$user = User::login($email, $password);
		if (Account::isLogined()) Session::setId(Application::$user->id);
		else $this->addAlert(View::str('error_sign_in'));
	}
	
	public function getRedirect() {
		$url = post('_url');
		if ($url=='') $url = '/user';
		return new Redirect(View::str('sign_successfuly'), $url, 1500);
	}
	
}

?>