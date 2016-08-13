<?php

Util::inc('controllers', 'base/WebController.php');

class AccessDeniedController extends WebController {
	
	public function beforeRender() {
		$this->addAlert(View::str('access_denied'), 'danger');
	}
	
}

?>