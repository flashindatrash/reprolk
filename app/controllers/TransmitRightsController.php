<?php

include '../core/interfaces/IRedirect.php';

class TransmitRightsController extends BaseController implements IRedirect {
	
	public $transmit_group;
	public $transmited = false;
	public $users;
	
	public function beforeRender() {
		$this->transmit_group = User::CLIENT;
		
		if ($this->formValidate(['user'])) {
			$group = Account::getGroup();
			$this->transmited = Account::setGroup($this->transmit_group);
			
			if ($this->transmited) {
				$this->transmited = User::editGroupById(post('user'), $group);
			}
			
			if ($this->transmited) return;
		}
		
		$this->users = View::convertSelect(User::getAll(array('id', 'username'), $this->transmit_group, Account::getGid()), 'id', 'username');
		
		if (count($this->users)==0) {
			$this->addAlert(sprintf(View::str('error_transmit_not_users'), Account::getGid(), View::str($this->transmit_group)), 'warning');
		}
	}
	
	public function getContent() {
		if ($this->transmited) $this->pick('system/redirect');
		else if (count($this->users)>0) $this->pick('user/transmit');
	}
	
	public function getRedirect() {
		return new Redirect(View::str('transmit_successfuly'));
	}
	
}

?>