<?php

class OrderAddController extends BaseController {
	
	public function beforeRender() {
		
	}
	
	public function getContent() {
		$this->pick('order/add');
	}
	
}

?>