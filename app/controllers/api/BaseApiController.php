<?php

Util::inc('controllers', 'base/JSONController.php');

class BaseApiController extends JSONController implements IAuthentication {
	
	const FIELD_SAFE_MODE = 'safe_mode';
	
	protected $isAvailable = false;
	protected $isLogined = false;
	protected $isSafeMode = false;
	
	public $request;
	
	public function __construct() {
		//DELETE!
		$_POST = array_merge($_GET, $_POST);
	}
	
	public function beforeRender() {
		parent::beforeRender();
		
		if (!$this->isAvailable) {
			$this->addAlert(View::str('error_api_access_denied'), 'danger');
			return;
		} else if (!$this->isLogined) {
			$this->addAlert(View::str('error_api_not_logined'), 'danger');
			return;
		}
		
		//безопасный режим, в случаи в котором в базу ничего не инсертится и не апдетится
		$this->isSafeMode = toBool(get('safe_mode'));
		
		//проверим реквест
		if ($this->checkRequest()) {
			//выполним метод
			if ($this->execute()) {
				$this->success = true;
				$this->response = $this->responsed();
			}
		}
		
		//DELETE!
		$this->processingApi();
	}
	
	public function authenticate($isAvailable, $isLogined) {
		$this->isAvailable = $isAvailable;
		$this->isLogined = $isLogined;
	}
	
	//DELETE!
	protected function mergeAlerts($controller) {
		$this->alerts = array_merge($this->alerts, $controller->alerts);
	}
	
	//удовлетворяет ли потребностям
	public function checkRequest() {
		if (is_null($this->request)) {
			//лениво создадим форму реквеста
			$this->request = $this->createRequestForm();
		}
		return $this->formValidate($this->request->fieldsMandatory());
	}
	
	//выполнение апи
	public function execute() {
		return false;
	}
	
	//ответ
	public function responsed() {
		return array();
	}
	
	//дефолтный реквест, нужен только для тестового запроса
	public function getDefaultRequest() {
		return array();
	}
	
	//DELETE!
	protected function processingApi() {
		//выполнение апи, через эту функцию
	}
	
	public function createRequestForm() {
		$form = $this->createForm('ApiRequest');
		return $form;
	}
	
}

?>