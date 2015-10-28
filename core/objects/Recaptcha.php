<?php

class Recaptcha {
	
	private $config;
	
	public function __construct($config) {
		$this->config = $config;
	}
	
	public function check($response) {
		return true;
	}
	
}

?>