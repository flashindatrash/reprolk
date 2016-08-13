<?php

class ApiRoute extends Route {
	
	public function __construct($name, $path = '/', $permission = null) {
		parent::__construct($name, $path, $permission, Route::TYPE_HIDDEN);
	}
	
	public function controllerPath() {
		return 'api/' . parent::controllerPath();
	}
	
	//текст для ссылок для API Documentation
	public function linkText() {
		return $this->path;
	}
	
	//ссылка на документацию с тестовым выполнением апи
	private $executeRoute;
	protected function getExecuteRoute() {
		if (is_null($this->executeRoute)) {
			$this->executeRoute = self::byName(Route::API_EXECUTE);
		}
		return $this->executeRoute;
	}
	
	//все ссылки должны вести на другой роут API_EXECUTE
	public function forGet($get) {
		$method = 'method=' . $this->name;
		
		if (is_null($get)) {
			$get = $method;
		} else {
			$get .= $method;
		}
		
		return $this->getExecuteRoute()->path . '?' . $get;
	}
	
}

?>