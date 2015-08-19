<?php

class Application {

	public static $db;
    public static $config;
	public static $routes = array();
	public static $route;
	public static $lang;
	
	public function __construct($config) {
		self::$config = $config;
		session_start();
	}
	
	public function connect() {
		self::$db = new DataBaseManager(self::$config['database']);
	}
	
	public function addLang($file) {
		if (file_exists($file)) self::$lang = require $file;
	}
	
	public function addRoute($route) {
		self::$routes[$route->name] = $route;
	}
	
	public function getContent() {
		self::$route = $this->getCurrentRoute();
		$controller = $this->getCurrentFactory(self::$route->name);
		
		if (is_null($controller)) {
			throw new Exception(self::$route->name .'Controller not found');
		} else {
			if (is_null($controller->user)) {
				$controller = $this->getCurrentFactory('Login');
			}
			$controller->render();
		}
	}
	
	private function getCurrentFactory($name) {
		$className = $name . 'Controller';
		$fileName = self::$config['app']['controllers'] . $className . '.php';
		return require_class($fileName, $className);
	}
	
	private function getCurrentRoute() {
		if (!isset($_GET['_url'])) return new Route('Index');
		foreach (self::$routes as $route)
			if ($route->path==strtolower(substr($_GET['_url'], 0, strlen($route->path)))) return $route;
		return new Route('Error', $_GET['_url']);
	}
	
}

?>