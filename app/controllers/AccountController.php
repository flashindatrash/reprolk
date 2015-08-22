<?php

class AccountController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getContent() {
		$this->pick('user/get');
	}
	
}

?>