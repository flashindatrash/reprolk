<?php

class Redirect extends BaseModel {
	
	public $message;
	public $url;
	public $timeout;
	
	public function __construct($message, $url = '/', $timeout = 1000) {
		$this->message = $message;
		$this->url = $url;
		$this->timeout = $timeout;
	}
	
	public function sTimeout() {
		return $this->timeout/1000;
	}
	
}

?>