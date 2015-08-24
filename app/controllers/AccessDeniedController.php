<?php

class AccessDeniedController extends BaseController {
	
	public function beforeRender() {
		$this->addError($this->str('ACCESS_DENIED'));
	}
	
}

?>