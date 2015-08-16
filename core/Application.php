<?php

class Application {

	public static $db;
    public static $config;
	
	private $routes = array();
	
	public function __construct($config) {
		self::$config = $config;
	}
	
	public function connect() {
		self::$db = new DataBaseManager(self::$config['database']);
	}
	
	public function addRoute($name, $path) {
		$this->routes[$name] = $path;
	}
	
	public function getContent() {
		$name = $this->controllerName();
		$controller = $this->controllerFactory($name);
		if (is_null($controller)) {
			throw new Exception($name .'Controller not found');
		} else {
			$controller->render();
		}
	}
	
	private function controllerFactory($name) {
		$className = $name . 'Controller';
		$fileName = self::$config['app']['controllers'] . $className . '.php';
		if (file_exists($fileName)) require_once ($fileName);
		if (class_exists($className)) return new $className;
		return null;
	}
	
	private function controllerName() {
		if (!isset($_GET['_url'])) return 'Index';
		$url = $_GET['_url'];
		foreach ($this->routes as $key => $value)
			if ($value==strtolower(substr($url, 0, strlen($value)))) return $key;
		return 'Error';
	}
	
}

?>