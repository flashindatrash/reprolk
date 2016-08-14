<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('controllers', 'api/ApiLoginController.php');
Util::inc('interfaces', 'IRedirect.php');
Util::inc('objects', 'Recaptcha.php');

class LoginController extends WebController implements IRedirect {
	
	public $api;
	public $useCaptcha = false;
	
	public function beforeRender() {
		$this->api = new ApiLoginController();
		$recaptcha = new Recaptcha(Application::$config['recaptcha']);
		
		$this->view = 'system/login';
		
		if ($this->api->checkRequest()) {
			if (!isset($_POST['g-recaptcha-response']) || (hasPost('g-recaptcha-response') && $recaptcha->check(post('g-recaptcha-response')))) {
				if (!$this->api->execute()) {
					$this->addAlert(View::str('error_sign_in'));
					$this->useCaptcha = true;
				} else {
					$this->setTemplate('empty');
					$this->view = 'system/redirect';
				}
			} else {
				$this->addAlert(View::str('error_captcha'));
				$this->useCaptcha = true;
			}
		}
		
		$this->addJSfile('https://www.google.com/recaptcha/api.js');
	}
	
	//IRedirect
	public function getRedirect() {
		$url = Application::$routes->byName(Route::ORDER_ALL)->forGet(Auth::FIELD_KEY . '=' . Application::$user->auth_key);
		return new Redirect(View::str('sign_successfuly'), $url);
	}
	
}

?>