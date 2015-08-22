<?php

class AccessDeniedController extends BaseController {
	
	public function __construct() {
		parent::__construct();
		
		$this->addError($this->str('ACCESS_DENIED'));
	}
	
}

?>