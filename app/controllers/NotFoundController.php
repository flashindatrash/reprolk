<?php

class NotFoundController extends BaseController {
	
	public function beforeRender() {
		$this->addError(sprintf($this->str('NOT_FOUND'), get('_url')));
	}
	
}

?>