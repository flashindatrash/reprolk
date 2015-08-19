<?php

class BaseController {
	
	public $template = 'base';
	public $user;
	public $errors = array();
	
	public function __construct() {
		if (isset($_SESSION['user_id'])) $this->user = User::byId($_SESSION['user_id']);
	}
	
	public function addError($message) {
		$this->errors[] = $message;
	}
	
	public function getContent() {
		echo '';
	}
	
	public function str($name) {
		return isset(Application::$lang) && isset(Application::$lang[$name]) ? Application::$lang[$name] : $name;
	}
	
	public function pick($name) {
		$this->include_file(Application::$config['app']['content'] . $name . '.phtml');
	}
	
	public function render() {
		$this->include_file(Application::$config['app']['layouts'] . $this->template . '.phtml');
	}
	
	private function include_file($file) {
		if (file_exists($file)) include $file;
	}

}