<?php

class BaseController {
	
	private $template = 'base';
	private $title = 'Repropark';
	private $css_files = array('bootstrap.min', 'bootstrap-theme.min', 'repropark');
	private $js_files = array('jquery-1.11.3.min', 'bootstrap.min', 'repropark');
	private $js_params = array();
	private $errors = array();
	private $warnings = array();
	
	public function __construct() {
		
	}
	
	public function addCSSfile($css) {
		$this->css_file[] = $css;
	}
	
	public function addJSfile($js) {
		$this->js_files[] = $js;
	}
	
	public function addJSparam($name, $value) {
		$this->js_params[$name] = $value;
	}
	
	public function addError($message) {
		$this->errors[] = $message;
	}
	
	public function addWarning($message) {
		$this->warnings[] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getTitle() {
		echo $this->title;
	}
	
	public function getCSSfiles() {
		foreach ($this->css_files as $css) {
			echo '<link rel="stylesheet" href="' . Application::$config['public']['css'] . $css . '.css">';
		}
	}
	
	public function getJSfiles() {
		foreach ($this->js_files as $js) {
			echo '<script src="' . Application::$config['public']['js'] . $js . '.js"></script>';
		}
	}
	
	public function getJSparams() {
		$s = '<script>var params = {';
		foreach ($this->js_params as $key => $value) {
			$s .= $key . ': "' . $value . '",';
		}
		$s .= '};</script>';
		echo $s;
	}
	
	public function getWarnings() {
		foreach ($this->warnings as $warning) {
			echo '<div class="alert alert-warning" role="alert">' . $warning . '</div>';
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