<?php

class OrderAddController extends BaseController {
	
	public $photopolymers;
	
	public function beforeRender() {
		$this->photopolymers = View::convertSelect(GroupPhotopolymer::getAll(Application::$user->gid), 'pid', 'name');
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
	}
	
	public function getContent() {
		$this->pick('order/add');
	}
	
}

?>