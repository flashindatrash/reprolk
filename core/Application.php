<?php

class Application {

	public static $db;
    public static $config;
	public static $routes;
	public static $user;
	
	private static $lang;
	
	public function __construct($config) {
		self::$config = $config;
		session_start();
	}
	
	public function connect() {
		self::$db = new DataBaseManager(self::$config['database']);
		self::$user = SystemSession::hasId() ? User::byId(SystemSession::getId()) : null;
	}
	
	public function setRoutes($array) {
		self::$routes = new SystemRoutes($array);
	}
	
	public function addLang($file) {
		if (file_exists($file)) self::$lang = require $file;
	}
	
	public function getContent() {
		$route = self::$routes->current;
		$controller = $this->getFactory($route);
		
		if (is_null($controller)) {
			//контроллер не найден
			$controller = $this->getFactory(new Route('NotFound'));
		} else if (!$route->isAvailable()) {
			//если нет доступа к текущему роуту
			$controller = $this->getFactory(new Route('AccessDenied'));
		} else if (is_null(self::$user)) {
			//пользователь не залогинен
			$controller = $this->getFactory(new Route('Login'));
		}
		
		$controller->beforeRender();
		
		if (SystemSession::hasGroup()) {
			$controller->addWarning(sprintf($this->str('warning_view_as'), self::str(SystemSession::getGroup()), View::link('ViewAsCancel', self::str('cancel'))));
		}
		
		$controller->render();
	}
	
	public static function str($name) {
		$name = strtoupper($name);
		return isset(self::$lang) && isset(self::$lang[$name]) ? self::$lang[$name] : $name;
	}
	
	private function getFactory($route) {
		$name = $route->name;
		$className = $name . 'Controller';
		$fileName = self::$config['app']['controllers'] . $className . '.php';
		return require_class($fileName, $className);
	}
	
}

?>