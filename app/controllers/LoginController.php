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
					$this->addAlert(View::str('error_sign_in'));
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
	
	public function login() {
		Application::$user = User::login(post('email'), post('password'));
		$isLogined = Account::isLogined();
		if ($isLogined) {
			//сгенерируем новый authKey
			Account::setAuthKey(md5(Application::$user->email . '_' . Application::$user->password));
		}
		return $isLogined;
	}
	
	//IRedirect
	public function getRedirect() {
		$url = post('_url');
		if ($url=='') $url = Application::$routes->byName(Route::ORDER_ALL)->forGet(Auth::FIELD_KEY . '=' . Application::$user->auth_key);
		return new Redirect(View::str('sign_successfuly'), $url);
	}
	
}

?>