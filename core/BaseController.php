<?php

class BaseController {
	
	public $template = 'base';
	public $errors = array();
	
	public function __construct() {
		
	}
	
	public function addError($message) {
		$this->errors[] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getContent() {
		echo '';
	}
	
	public function str($name) {
		return Application::str($name);
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