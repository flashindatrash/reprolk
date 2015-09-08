<?php

class ProfileController extends BaseController {
	
	public function getContent() {
		$this->pick('user/index');
	}
	
}

?>