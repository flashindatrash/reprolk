<?php

class UserAllController extends BaseController {
	
	public function getContent() {
		$this->pick('system/in_progress');
		$this->pick('index/index');
	}
	
}

?>