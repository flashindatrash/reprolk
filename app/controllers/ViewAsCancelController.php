<?php

include '../core/interfaces/IRedirect.php';

class ViewAsCancelController extends BaseController implements IRedirect {
	
	public function beforeRender() {
		Session::setGroup(null);
		Session::setGid(null);
		$this->setTemplate('empty');
		$this->view = 'system/redirect';
	}
	
	public function getRedirect() {
		return new Redirect(View::str('sign_successfuly'));
	}

}