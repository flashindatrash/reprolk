<?php

class BaseController {
	
	private $template = 'base';
	private $javascripts = array();
	private $javascript_params = array();
	private $errors = array();
	
	public function __construct() {
		
	}
	
	public function addJS($js) {
		$this->javascripts[] = $js;
	}
	
	public function addJSparams($name, $value) {
		$this->javascript_params[$name] = $value;
	}
	
	public function addError($message) {
		$this->errors[] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getJavascriptParams() {
		$s = '<script>var params = {';
		foreach ($this->javascript_params as $key => $value) {
			$s .= $key . ': "' . $value . '"';
		}
		$s .= '}</script>';
		echo $s;
	}
	
	public function getJavascripts() {
		foreach ($this->javascripts as $js) {
			echo '<script src="/js/' . $js . '.js"></script>';
		}
	}
	
	public function getErrors() {
		foreach ($this->errors as $error) {
			echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
		}
	}
	
	public function getMenu() {
		$this->pick('system/menu');
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