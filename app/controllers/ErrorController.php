<?php

class ErrorController extends BaseController {
	
	public function __construct() {
		parent::__construct();
		
		$this->addError(get('_url') . ' not found');
	}
	
}

?>