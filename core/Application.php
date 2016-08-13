<?php

class Application {

	public static $db;
	public static $ftp;
    public static $config;
	public static $routes;
	public static $user;
	public static $cookie;
	
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
		//подключение к БД
		self::$db->connect();
		
		self::$cookie = new CookieStorage($_COOKIE);
		
		//авторизация
		if (hasPost(Auth::FIELD_KEY)) { //если есть ключ в POST'e
			self::$user = Auth::userByKey(post(Auth::FIELD_KEY));
		} else if (hasGet(Auth::FIELD_KEY)) { //если есть ключ в GET'e
			self::$user = Auth::userByKey(get(Auth::FIELD_KEY));
		} else if (self::$cookie->offsetExists(Auth::FIELD_KEY)) { //если есть ключ в Cookie
			self::$user = Auth::userByKey(self::$cookie[Auth::FIELD_KEY]);
		}
		
		//обновим в сессии ключ
		if (!is_null(self::$user)) {
			self::$cookie[Auth::FIELD_KEY] = Application::$user->auth_key;
		}
		
		//загрузка плагинов
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
		$controller = !is_null($route) ? self::getFactory($route) : null;
		
		if (is_null($controller)) {
			//контроллер не найден
			$controller = self::getFactory(self::$routes->byName(Route::NOT_FOUND));
		} else if ($controller instanceof IAuthentication) {
			//специальный контроллер, который не требует редиректа
			$controller->authenticate($route->isAvailable(), Account::isLogined());
		} else if (!$route->isAvailable()) {
			if (!Account::isLogined()) {
				//пользователь не залогинен
				$controller = self::getFactory(self::$routes->byName(Route::LOGIN));
			} else {
				//если нет доступа к текущему роуту
				$controller = self::getFactory(self::$routes->byName(Route::ACCESS_DENIED));
			}
		} else {
			//все ок
		}
		
		
		if (!is_null($route)) {
			if (Account::isLogined()) {
				//история посещений
				UserHistory::add($route->name);
			}
			
			//подключим плагины
			foreach (self::$plugins as $plugin) {
				$plugin->connect();
			}
		}
		
		$controller->beforeRender();
		
		hook(HOOK_APPLICATION_RENDER, null, $controller);
		
		$controller->render();
	}
	
	public static function str($name) {
		$name = strtoupper($name);
		return isset(self::$lang) && isset(self::$lang[$name]) && strlen(self::$lang[$name])>0 ? self::$lang[$name] : $name;
	}
	
	public static function getFactory($route) {
		$fileName = self::$config['app']['controllers'] . $route->controllerPath();
		return require_class($fileName, $route->controllerName());
	}
	
}