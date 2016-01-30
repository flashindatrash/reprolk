<?php

class Routes {
	
	private $dictByName = array();
	private $dictByPath = array();
	
	public $all;
	public $current;
	
	public function __construct($array) {
		$this->all = $array;
		$this->parse($this->all);
		$this->current = $this->getCurrent();
	}
	
	public function byPath($path) {
		return array_key_exists($path, $this->dictByPath) ? $this->dictByPath[$path] : null;
	}
	
	public function byName($name) {
		return array_key_exists($name, $this->dictByName) ? $this->dictByName[$name] : null;
	}
	
	public function equalRoute($route) {
		return !is_null($this->current) && $this->current->name == $route->name;
	}
	
	public function currentPath() {
		return !is_null($this->current) ? $this->current->path : '';
	}
	
	public function parseUrl($url) {
		$u = strtolower($url);
		$delim = strpos($u, '?');
		if ($delim!==false) $u = substr($u, 0, $delim);
		return $this->byPath($u);
	}
	
	private function parse($routes) {
		foreach ($routes as $route) {
			$this->dictByName[$route->name] = $route;
			$this->dictByPath[$route->path] = $route;
			$this->parse($route->routes);
		}
	}
	
	private function getCurrent() {
		if (!hasGet('_url')) return $this->byName(Route::ORDER_ALL);
		return $this->parseUrl(get('_url'));
	}
}

?>