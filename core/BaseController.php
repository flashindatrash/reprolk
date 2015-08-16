<?php

class BaseController {
	
	public $layout = 'base';
	
	public function __construct() {
		
	}
	
	public function getContent() {
		return '';
	}
	
	public function render() {
		$file = Application::$config['app']['layouts'] . $this->layout . '.phtml';
		if (file_exists($file)) include $file;
	}

}