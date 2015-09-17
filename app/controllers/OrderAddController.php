<?php

class OrderAddController extends BaseController {
	
	public $photopolymers_values = array();
	public $photopolymers_keys = array();
	
	
	public function beforeRender() {
		$photopolymers = Photopolymer::getAll();
		foreach ($photopolymers as $photopolymer) {
			$this->photopolymers_keys[] = $photopolymer->id;
			$this->photopolymers_values[] = $photopolymer->name;
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
	}
	
	public function getContent() {
		$this->pick('order/add');
	}
	
}

?>