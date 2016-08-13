<?php

Util::inc('controllers', 'base/WebController.php');

class NotFoundController extends WebController {
	
	public function beforeRender() {
		$this->addAlert(sprintf(View::str('not_found'), get('_url')), 'danger');
	}
	
}

?>