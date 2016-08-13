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
	const ORDER_REPEAT = 'OrderRepeat';
	const ORDER_ARCHIVE = 'OrderArchive';
	const ORDER_APPROVAL = 'OrderApproval';
	const ORDER_APPROVED = 'OrderApproved';
	const ORDER_DISAPPROVED = 'OrderDisapproved';
	const POLYMER_ALL = 'PolymerAll';
	const POLYMER_BIND = 'PolymerBind';
	const POLYMER_DELETE = 'PolymerDelete';
	const PROFILE = 'User';
	const USER_ADD = 'UserAdd';
	const USER_ALL = 'UserAll';
	const USER_HISTORY = 'UserHistory';
	const TRANSMIT_RIGHTS = 'TransmitRights';
	const ADMIN = 'Admin';
	const VIEW_AS = 'ViewAs';
	const VIEW_AS_CANCEL = 'ViewAsCancel';
	const COMMENT_DELETE = 'CommentDelete';
	const FIELD_PAGES = 'FieldPages';
	const FIELD_PAGE = 'FieldPage';
	const FIELD_DELETE = 'FieldDelete';
	const FIELD_ADD = 'FieldAdd';
	const FIELD_BIND = 'FieldBind';
	const TEMPLATE_VIEW = 'TemplateView';
	const TEMPLATE_EDIT = 'TemplateEdit';
	const TEMPLATE_DELETE = 'TemplateDelete';
	const LOCALE_ALL = 'LocaleAll';
	const LOCALE_STATS = 'LocaleStats';
	const LOCALE_EDIT = 'LocaleEdit';
	const LANGUAGE_SET = 'LanguageSet';
	const API_DOCUMENTATION = 'ApiDocumentation';
	const API_EXECUTE = 'ApiExecute';
	const CRON = 'Cron';
	const SWITCH_PLUGIN = 'SwitchPlugin';
	const API_LOGIN = 'ApiLogin';
	const API_ORDER_ADD = 'ApiOrderAdd';
	const API_ORDER_ALL = 'ApiOrderAll';
	const API_ORDER_CANCEL = 'ApiOrderCancel';
	const API_ORDER_REPEAT = 'ApiOrderRepeat';
	const API_FIELD_GET = 'ApiFieldGet';
	
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
	
	//клонирование роута
	public function __clone() {
		return new Route($this->name, $this->path, $this->permission, $this->type);
	}
	
	//текст для ссылок
	public function linkText() {
		return Application::str('menu_' . $this->name);
	}
	
	//текст в хлебных крошках
	public function breadcrumpText() {
		return $this->linkText();
	}
	
	//текст в ссылках при наведении
	public function linkTitle() {
		return Application::str('menu_title_' . $this->name);
	}
	
	//доступно ли
	public function isAvailable() {
		return is_null($this->permission) || UserAccess::check($this->permission);
	}
	
	//виден ли в меню
	public function isVisible() {
		return $this->type!=Route::TYPE_HIDDEN && $this->isAvailable();
	}
	
	//путь контроллера
	public function controllerPath() {
		return $this->controllerName() . '.php';
	}
	
	//имя контроллера
	public function controllerName() {
		return $this->name . 'Controller';
	}
	
	//вернуть путь с GET'ом
	public function forGet($get) {
		return $this->path . (!is_null($get) ? '?' . $get : '');
	}
	
	public static function byName($name) {
		return Application::$routes->byName($name);
	}
	
}