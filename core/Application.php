<?php

class Application {

	public static $db;
    public static $config;
	public static $routes = array();
	public static $route;
	public static $user;
	
	private static $lang;
	
	public function __construct($config) {
		self::$config = $config;
		session_start();
	}
	
	public function connect() {
		self::$db = new DataBaseManager(self::$config['database']);
		if (isset($_SESSION['user_id'])) self::$user = User::byId($_SESSION['user_id']);
	}
	
	public function addLang($file) {
		if (file_exists($file)) self::$lang = require $file;
	}
	
	public function addRoute($route) {
		self::$routes[$route->name] = $route;
	}
	
	public function getContent() {
		self::$route = $this->getRoute();
		$controller = $this->getFactory(self::$route->name);
		
		if (is_null($controller)) {
			$controller = $this->getFactory('Error');
		} else if (is_null(self::$user)) {
			$controller = $this->getFactory('Login');
		}
		
		$controller->render();
	}
	
	public static function str($name) {
		$name = strtoupper($name);
		return isset(self::$lang) && isset(self::$lang[$name]) ? self::$lang[$name] : $name;
	}
	
	private function getFactory($name) {
		$className = $name . 'Controller';
		$fileName = self::$config['app']['controllers'] . $className . '.php';
		return require_class($fileName, $className);
	}
	
	private function getRoute() {
		if (!isset($_GET['_url'])) return new Route('Index');
		foreach (self::$routes as $route)
			if ($route->path==strtolower(substr($_GET['_url'], 0, strlen($route->path)))) return $route;
		return new Route('Error', $_GET['_url']);
	}
}

?>