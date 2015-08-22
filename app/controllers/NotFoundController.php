<?php

class NotFoundController extends BaseController {
	
	public function __construct() {
		parent::__construct();
		
		$this->addError(sprintf($this->str('NOT_FOUNT'), get('_url')));
	}
	
}

?>