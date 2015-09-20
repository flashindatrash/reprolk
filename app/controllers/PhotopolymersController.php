<?php

class PhotopolymersController extends BaseController {
	
	public $photopolymers;
	
	public function beforeRender() {
		$this->photopolymers = Photopolymer::getAll();
	}
	
	public function getContent() {
		$this->pick('admin/photopolymers');
	}
	
}

?>