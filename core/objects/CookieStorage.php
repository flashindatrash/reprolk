<?php

class CookieStorage implements ArrayAccess {
	
	// сколько по умолчанию будем хранить куки
	const DEFAULT_EXPIRE_TIME = 1411200; // 2 недели

	// хранилище данных
	private $_storage;

	// конструктор для записи данных из родного массива $_COOKIE
	public function __construct($cookies) {
		$this->_storage = $cookies;
	}

	// стандартный метод ArrayAccess для проверки существования элемента
	public function offsetExists ($offset) {
		return isset ($this->_storage[$offset]);
	}

	// стандартный метод ArrayAccess для удаления элемента
	public function offsetUnset ($offset) { 
		unset($this->_storage[$offset]);
	}

	// метод для получения значение конкретной куки
	public function offsetGet ($offset) {      
		return $this->_storage[$offset];
	}

	// метод для задания значения куки
	public function offsetSet ($offset, $value) {
		if( $this->_setCookie($offset, $value) ) {
			$this->_storage[$offset] = $value;
		} else{
			trigger_error('Cookie value was not set', E_USER_WARNING);
		}
	}

	// обертка для функции setcookie
	private function _setCookie( $name, $value, $expire = 0, $path = '/', $domain = false, $secure = false , $httponly = false ){
		if (!headers_sent()){
			if ($domain === false) {
				$domain = $_SERVER['HTTP_HOST'];
			}
			if ($expire == 0 ) {
				$expire = time() + self::DEFAULT_EXPIRE_TIME;
			}
			return setcookie ( $name, $value, $expire, $path, $domain, $secure, $httponly );
		}
		return false;
	}
}