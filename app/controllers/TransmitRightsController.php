<?php

include '../core/interfaces/IRedirect.php';

class TransmitRightsController extends BaseController implements IRedirect {
	
	public $transmit_group;
	public $users;
	
	public function beforeRender() {
		$this->transmit_group = User::CLIENT;
		
		if ($this->formValidate(['user'])) {
			$group = Account::getGroup();
			
			if (Account::setGroup($this->transmit_group) && User::editGroupById(post('user'), $group)) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
				return;
			} else {
				$this->addAlert(View::str('error_transmit_something'));
			}
		}
		
		$this->users = reArray(User::getAll(array('id', 'username'), $this->transmit_group, Account::getGid()), 'id', 'username');
		
		if (count($this->users)==0) {
			$this->addAlert(sprintf(View::str('error_transmit_not_users'), Account::getGid(), View::str($this->transmit_group)), 'warning');
		} else {
			$this->view = 'admin/user/transmit-rights';
		}
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('transmit_successfuly'));
	}
	
}

?>