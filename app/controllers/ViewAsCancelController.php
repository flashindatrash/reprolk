<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IRedirect.php');

class ViewAsCancelController extends WebController implements IRedirect {
	
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