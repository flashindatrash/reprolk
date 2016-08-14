<?php

class BaseController {
	
	private $template = 'base';
	private $title = 'Repropark';
	
	public $alerts = array('danger' => [], 'warning' => [], 'info' => [], 'success' => []);
	public $view;
	
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function addAlert($message, $type = 'danger') {
		$this->alerts[$type][] = $message;
	}
	
	public function beforeRender() {
		//до того как загрузиться шаблон мы должны заинитить контроллер
	}
	
	public function getTitle() {
		print $this->title;
	}
	
	public function getContent() {
		if (is_null($this->view)) return;
		$this->pick($this->view);
	}
	
	public function formValidate($fields) {
		$valid = true;
		foreach ($fields as $field) {
			if (!hasPost($field)) {
				$valid = false;
				$this->addAlert(sprintf(View::str('must_enter'), View::str($field)), 'danger');
			}
		}
		return $valid;
	}
	
	public function formValues($fields) {
		$values = array();
		foreach ($fields as $field) {
			$values[] = post($field);
		}
		return $values;
	}
	
	public function pick($path) {
		$this->include_file(Application::$config['app']['content'] . $path . '.phtml');
		//Util::inc('content', $path . '.phtml');
	}
	
	public function render() {
		$this->include_file(Application::$config['app']['layouts'] . $this->template . '.phtml');
		//Util::inc('layouts', $this->template . '.phtml');
	}
	
	public function createForm($name) {
		$this->include_file(Application::$config['app']['forms'] . $name . '.php');
		//Util::inc('forms', $name . '.php');
		
		$className = $name . 'Form';
		if (class_exists($className)) {
			return new $className();
		} else {
			$this->addAlert(sprintf('Fail Form: path %s not found class %s', $php, $className), 'danger');
		}
		return null;
	}
	
	protected function include_file($file) {
		if (file_exists($file)) include_once $file;
	}
}