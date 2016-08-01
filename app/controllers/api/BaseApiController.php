<?php

include_once '../app/controllers/JSONController.php';

class BaseApiController extends JSONController implements IAuthentication {
	
	protected $isAvailable = false;
	protected $isLogined = false;
	
	function __construct() {
		//если включены get запросы, то примержим их в post
		if (Application::$config['admin']['apiUseGet']==1) {
			$_POST = array_merge($_GET, $_POST);
		}
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
	
	}
	
}

?>