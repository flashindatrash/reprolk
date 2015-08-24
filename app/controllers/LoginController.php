<?php

class LoginController extends BaseController {
	
	public function beforeRender() {
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$this->login(post('email'), post('password'));
		}
	}
	
	public function getContent() {
		if (is_null(Application::$user)) {
			$this->pick('login/index');
		} else {
			$this->pick('system/redirect');
		}
	}
	
	public function getMessage() {
		return $this->str('SIGN_SUCCESSFULY');
	}
	
	public function getURL() {
		return post('_url');
	}
	
	private function login($email, $password) {
		Application::$user = User::login($email, $password);
		if (!is_null(Application::$user)) $_SESSION['user_id'] = Application::$user->id;
		else $this->addError($this->str('ERROR_SIGN_IN'));
	}
	
}

?>