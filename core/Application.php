<?php

class Application {

	public static $db;
	public static $ftp;
    public static $config;
	public static $routes;
	public static $user;
	
	private static $lang;
	private static $plugins;
	
	public function __construct($config) {
		Config::check_client();
		self::$config = $config;
		self::$db = new DataBaseManager(self::$config['database']);
		self::$ftp = new FTPManager(self::$config['ftp']);
		session_start();
	}
	
	public function connect() {
		self::$db->connect();
		self::$user = Session::hasId() ? User::byId(Session::getId()) : null;
		self::$plugins = !is_null(self::$user) ? UserPlugin::getAll(self::$user->id, self::$user->gid) : [];
	}
	
	public function setRoutes($array) {
		self::$routes = new Routes($array);
	}
	
	public function setLang($lang) {
		self::$lang = reArray(Locale::getAll($lang), Locale::KEY, $lang);
	}
	
	public function getContent() {
		$route = self::$routes->current;
		$controller = !is_null($route) ? $this->getFactory($route) : null;
		
		if (is_null($controller)) {
			//контроллер не найден
			$controller = $this->getFactory(self::$routes->byName(Route::NOT_FOUND));
		} else if (!$route->isAvailable()) {
			if (!Account::isLogined()) {
				//пользователь не залогинен
				$controller = $this->getFactory(self::$routes->byName(Route::LOGIN));
			} else {
				//если нет доступа к текущему роуту
				$controller = $this->getFactory(self::$routes->byName(Route::ACCESS_DENIED));
			}
		} else {
			//если все ок
			//подключим плагины
			foreach (self::$plugins as $plugin) {
				$plugin->connect($this->controllerByRoute($route) . '.php');
			}
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
		return isset(self::$lang) && isset(self::$lang[$name]) && strlen(self::$lang[$name])>0 ? self::$lang[$name] : $name;
	}
	
	private function getFactory($route) {
		$className = $this->controllerByRoute($route);
		$fileName = self::$config['app']['controllers'] . $className . '.php';
		return require_class($fileName, $className);
	}
	
	private function controllerByRoute($route) {
		return $route->name . 'Controller';
	}
	
}