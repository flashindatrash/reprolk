<?php

class IndexController extends BaseController {
	
	public function getContent() {
		$this->pick('index/index');
	}
	
}

?>