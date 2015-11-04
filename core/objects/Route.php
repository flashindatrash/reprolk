<?php

class Route {
	
	const INDEX = 'Index';
	const FILE = 'File';
	const LOGIN = 'Login';
	const LOGOUT = 'Logout';
	const NOT_FOUND = 'NotFound';
	const ACCESS_DENIED = 'AccessDenied';
	const ORDER_ADD = 'OrderAdd';
	const ORDER_VIEW = 'OrderView';
	const ORDER_EDIT = 'OrderEdit';
	const ORDER_CANCEL = 'OrderCancel';
	const ORDER_DELETE = 'OrderDelete';
	const ORDER_DUPLICATE = 'OrderDuplicate';
	const ORDER_ALL = 'OrderAll';
	const GROUP_POLYMERS = 'GroupPhotopolymers';
	const POLYMERS = 'Photopolymers';
	const POLYMER_DELETE = 'PhotopolymerDelete';
	const PROFILE = 'User';
	const USER_ADD = 'UserAdd';
	const USER_ALL = 'UserAll';
	const TRANSMIT_RIGHTS = 'TransmitRights';
	const ADMIN = 'Admin';
	const VIEW_AS = 'ViewAs';
	const VIEW_AS_CANCEL = 'ViewAsCancel';
	const COMMENT_DELETE = 'CommentDelete';
	const FIELD_PAGES = 'FieldPages';
	const FIELD_EDIT = 'FieldEdit';
	const FIELD_DELETE = 'FieldDelete';
	
	const TYPE_NORMAL = 0;
	const TYPE_SUB = 1;
	const TYPE_HIDDEN = 2;
	
	public $name;
	public $path;
	public $permission;
	public $type;
	public $routes;
	
	public function __construct($name, $path = '/', $permission = null, $type = 0, $routes = null) {
		$this->name = $name;
		$this->path = $path;
		$this->permission = $permission;
		$this->type = $type;
		$this->routes = is_null($routes) ? [] : $routes;
	}
	
	public function linkText() {
		return Application::str('menu_' . $this->name);
	}
	
	public function linkTitle() {
		return Application::str('menu_title_' . $this->name);
	}
	
	public function isAvailable() {
		return is_null($this->permission) || UserAccess::check($this->permission);
	}
	
	public function isVisible() {
		return $this->type!=Route::TYPE_HIDDEN && $this->isAvailable();
	}
	
}

?>