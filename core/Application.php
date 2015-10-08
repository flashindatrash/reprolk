<?php

class Application {

	public static $db;
	public static $ftp;
    public static $config;
	public static $routes;
	public static $user;
	
	private static $lang;
	
	public function __construct($config) {
		self::$config = $config;
		self::$db = new DataBaseManager(self::$config['database']);
		self::$ftp = new FTPManager(self::$config['ftp']);
		session_start();
	}
	
	public function connect() {
		self::$db->connect();
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
		} else if (!Account::isLogined()) {
			//пользователь не залогинен
			$controller = $this->getFactory(self::$routes->byName(Route::LOGIN));
		} else if (!$route->isAvailable()) {
			//если нет доступа к текущему роуту
			$controller = $this->getFactory(self::$routes->byName(Route::ACCESS_DENIED));
		}
		
		$controller->beforeRender();
		
		if (Session::hasGroup()) {
			//уведомление что пользователь просматривает страницу как ...
			$controller->addAlert(sprintf($this->str('warning_view_as'), self::str(Session::getGroup()), View::link(Route::VIEW_AS_CANCEL, self::str('cancel'))), 'warning');
		}
		
		if (self::$config['admin']['displaySQL']==1) {
			//все sql запросы в beforeRender отобразятся на странице
			$sql_history = self::$db->getHistory();
			foreach ($sql_history as $sql) {
				$controller->addAlert($sql, 'info');
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