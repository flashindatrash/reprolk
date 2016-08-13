<?php

Util::inc('controllers', 'base/WebController.php');

class UserAddController extends WebController {
	
	public $groups;
	public $gid;
	
	private $user_id = null;
	
	public function beforeRender() {
		$const = 'UserAccess::USER_ADD_' . strtoupper(Account::getGroup());
		$access = defined($const) ? constant($const) : UserAccess::NONE;
		
		$this->groups = UserAccess::$permissions[$access];
		$this->gid = !Account::isAdmin() ? Account::getGid() : null;
		
		$fields = User::$fields_mandatory;
		
		if ($this->formValidate($fields)) {
			$this->user_id = User::add($fields, $this->formValues($fields));
			if (!is_null($this->user_id)) {
				$this->addAlert(View::str('success_save'), 'success');
			} else {
				$this->addAlert(View::str('error_create_user'), 'danger');
			}
		}
		
		$this->view = 'admin/user/add';
	}
}

?>