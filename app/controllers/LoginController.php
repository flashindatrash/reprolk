<?php

class LoginController extends BaseController {
	
	public function __construct() {
		if (isset($_POST['email']) && isset($_POST['password'])) {
			$this->login(post('email'), post('password'));
		}
		parent::__construct();
	}
	
	public function getContent() {
		if (is_null(Application::$user)) {
			echo $this->pick('login/index');
		} else {
			echo $this->pick('login/success');
		}
	}
	
	private function login($email, $password) {
		Application::$user = User::login($email, $password);
		if (!is_null(Application::$user)) $_SESSION['user_id'] = Application::$user->id;
		else $this->addError($this->str('ERROR_SIGN_IN'));
	}
	
}

?>