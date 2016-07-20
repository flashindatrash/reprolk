<?php

class TestController extends BaseController {
	
	public function beforeRender() {
		$this->view = 'admin/test';
	}
	
}