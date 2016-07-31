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
	
	private $controller;
	
	public function beforeRender() {
		$this->isLogined = true; //подхачим, чтобы вызвать processingApi
		parent::beforeRender();
	}
	
	public function processingApi() {
		$this->controller = new LoginController();
		
		$this->success = $this->controller->login();
		
		if ($this->success) {
			$this->response[Auth::POST_KEY] = Account::getAuthKey();
		}
	}
	
}

?>