<?php

class AccessDeniedController extends BaseController {
	
	public function beforeRender() {
		$this->addAlert(View::str('access_denied'), 'danger');
	}
	
}

?>