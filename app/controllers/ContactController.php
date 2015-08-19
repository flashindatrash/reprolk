<?php

class ContactController extends BaseController {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function getContent() {
		echo 'contacts';
	}
	
}

?>