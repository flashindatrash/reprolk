<?php

class NotFoundController extends BaseController {
	
	public function beforeRender() {
		$this->addAlert(sprintf(View::str('not_found'), get('_url')), 'danger');
	}
	
}

?>