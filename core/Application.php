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
		self::$user = Session::hasId() ? User::byId(Session::getId()) : null;
	}
	
	public function setRoutes($array) {
		self::$routes = new Routes($array);
	}
	
	public function addLang($file) {
		if (file_exists($file)) self::$lang = require $file;
	}
	
	public function getContent() {
		$route = self::$routes->current;
		$controller = !is_null($route) ? $this->getFactory($route) : null;
		
		if (is_null($controller)) {
			//контроллер не найден
			$controller = $this->getFactory(self::$routes->byName(Route::NOT_FOUND));
		} else if (!$route->isAvailable()) {
			//если нет доступа к текущему роуту
			$controller = $this->getFactory(self::$routes->byName(Route::ACCESS_DENIED));
		} else if (!Account::isLogined()) {
			//пользователь не залогинен
			$controller = $this->getFactory(self::$routes->byName(Route::LOGIN));
		}
		
		$controller->beforeRender();
		
		if (Session::hasGroup()) {
			//уведомление что пользователь просматривает страницу как ...
			$controller->addWarning(sprintf($this->str('warning_view_as'), self::str(Session::getGroup()), View::link('ViewAsCancel', self::str('cancel'))));
		}
		
		if (self::$config['admin']['displaySQL']==1) {
			//все sql запросы в beforeRender отобразятся на странице
			$sql_history = self::$db->getHistory();
			foreach ($sql_history as $sql) {
				$controller->addWarning($sql);
			}
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