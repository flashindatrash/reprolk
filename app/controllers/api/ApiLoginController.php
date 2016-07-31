<?php

include_once '../app/controllers/api/BaseApiController.php';
include_once '../app/controllers/LoginController.php';

/*
params:
	email
	password
	
response:
	authKey
*/

class ApiLoginController extends BaseApiController {
	
	public function beforeRender() {
		$this->isLogined = true; //подхачим, чтобы вызвать processingApi
		parent::beforeRender();
	}
	
	public function processingApi() {
		$this->success = LoginController::login();
		if ($this->success) {
			$this->response[Auth::POST_KEY] = Account::getAuthKey();
		}
	}
	
}

?>