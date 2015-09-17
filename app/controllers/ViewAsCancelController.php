<?php

include '../core/interfaces/IRedirect.php';

class ViewAsCancelController extends BaseController implements IRedirect {
	
	public function beforeRender() {
		SystemSession::setGroup(null);
	}
	
	public function getContent() {
		$this->pick('system/redirect');
	}
	
	public function getRedirect() {
		return new Redirect($this->str('sign_successfuly'));
	}

}