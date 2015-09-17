<?php

class NotFoundController extends BaseController {
	
	public function beforeRender() {
		$this->addError(sprintf($this->str('not_found'), get('_url')));
	}
	
}

?>