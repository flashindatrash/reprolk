<?php

include_once '../app/controllers/JSONController.php';

class BaseApiController extends JSONController implements IAuthentication {
	
	protected $isAvailable = false;
	protected $isLogined = false;
	protected $request;
	
	public function __construct() {
		$_POST = array_merge($_GET, $_POST);
	}
	
	public function beforeRender() {
		parent::beforeRender();
		
		if (!$this->isAvailable) {
			$this->addAlert(View::str('error_api_access_denied'), 'danger');
		} else if (!$this->isLogined) {
			$this->addAlert(View::str('error_api_not_logined'), 'danger');
		} else {
			$this->processingApi();
		}
	}
	
	public function authenticate($isAvailable, $isLogined) {
		$this->isAvailable = $isAvailable;
		$this->isLogined = $isLogined;
	}
	
	protected function addPostValidator() {
		$_POST[BaseController::POST_VALIDATOR] = "1";
	}
	
	protected function mergeAlerts($controller) {
		$this->alerts = array_merge($this->alerts, $controller->alerts);
	}
	
	protected function processingApi() {
		$this->request = $this->createRequestForm();
		//выполнение апи, через эту функцию
	}
	
	public function createRequestForm() {
		$form = $this->createForm('ApiRequest');
		return $form;
	}
	
}

?>