<?php

Util::inc('controllers', 'api/BaseApiController.php');

/*
params:
	email
	password
	
response:
	auth_key
*/

class ApiLoginController extends BaseApiController {
	
	public function beforeRender() {
		$this->isLogined = true; //подхачим, чтобы вызвать processingApi
		parent::beforeRender();
	}
	
	public function execute() {
		Application::$user = User::login(post(User::FIELD_EMAIL), post(User::FIELD_PASSWORD));
		
		if (!Account::isLogined()) {
			$this->addAlert(View::str('error_sign_in'), 'danger');
			return false;
		}
		
		//сгенерируем новый authKey
		Account::setAuthKey(md5(Application::$user->email . '_' . Application::$user->password));
		return true;
	}
	
	public function responsed() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey()
		);
	}
	
	public function getDefaultRequest() {
		return array(
			User::FIELD_EMAIL => Account::getEmail(),
			User::FIELD_PASSWORD => Account::getPassword()
		);
	}
	
	public function createRequestForm() {
		$form = parent::createRequestForm();
		$form->addField(User::FIELD_EMAIL, INPUT_EMAIL, true);
		$form->addField(User::FIELD_PASSWORD, INPUT_PASSWORD, true);
		return $form;
	}
	
}

?>