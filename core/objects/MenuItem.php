<?php

class MenuItem extends BaseModel {
	
	public $items = array();
	public $routeName;
	public $type;
	
	public function __construct($route) {
		$this->items = self::parse($route->routes);
		$this->routeName = $route->name;
		$this->type = $route->type;
	}
	
	public function linkText() {
		return Application::$routes->byName($this->routeName)->linkText();
	}
	
	public function breadcrumpText() {
		return Application::$routes->byName($this->routeName)->breadcrumpText();
	}
	
	public function isActive() {
		return $this->currentRoute() == $this->routeName;
	}
	
	public function isCollapsed() {
		return count($this->items)>0;
	}
	
	public function isFinded() {
		return $this->isActive() || $this->findInArray($this->items);
	}
	
	public function isExpanded() {
		return $this->isCollapsed() && $this->isFinded();
	}
	
	public function liClass() {
		$li = 'leaf';
		if ($this->isExpanded()) $li = 'expanded';
		else if ($this->isCollapsed()) $li = 'collapsed'; 
		
		if ($this->isFinded()) $li .= ' active-trail';
		return $li;
	}
	
	private function findInArray($items) {
		foreach ($items as $item) {
			if ($this->currentRoute() == $item->routeName || $this->findInArray($item->items)) return true;
		}
		return false;
	}
	
	public static function parse($routes) {
		$a = array();
		foreach ($routes as $i => $route) {
			$item = new MenuItem($route);
			$a[] = $item;
		}
		return $a;
	}
	
	private function currentRoute() {
		return Application::$routes->current ? Application::$routes->current->name : null;
	}
}

?>