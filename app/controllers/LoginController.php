<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../core/objects/Recaptcha.php';

class LoginController extends BaseController implements IRedirect {
	
	public $useCaptcha = false;
	
	public function beforeRender() {
		$recaptcha = new Recaptcha(Application::$config['recaptcha']);
		$formValidate = $this->formValidate(['email', 'password']);
		$captchaValidate = hasPost('g-recaptcha-response') && $recaptcha->check(post('g-recaptcha-response'));
		$this->view = 'system/login';
		
		if ($formValidate) {
			if (!isset($_POST['g-recaptcha-response']) || $captchaValidate) {
				if (!$this->login()) {
					$this->useCaptcha = true;
				} else {
					$this->view = 'system/redirect';
				}
			} else {
				$this->addAlert(View::str('error_captcha'));
				$this->useCaptcha = true;
			}
		}
		
		$this->addJSfile('https://www.google.com/recaptcha/api.js');
	}
	
	private function login() {
		Application::$user = User::login(post('email'), post('password'));
		if (Account::isLogined()) Session::setId(Application::$user->id);
		else $this->addAlert(View::str('error_sign_in'));
		return Account::isLogined();
	}
	
	//IRedirect
	public function getRedirect() {
		$url = post('_url'); 
		if ($url=='') $url = Application::$routes->byName(Route::ORDER_ALL)->path;
		return new Redirect(View::str('sign_successfuly'), $url, 1500);
	}
	
}

?>