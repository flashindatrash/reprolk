<?php

class AccountController extends BaseController {
	
	public function getContent() {
		$this->pick('user/index');
	}
	
}

?>