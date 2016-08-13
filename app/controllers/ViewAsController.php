<?php

Util::inc('controllers', 'base/WebController.php');

class ViewAsController extends WebController {
	
	public $groups;
	public $gids;
	public $gid;
	
	public function beforeRender() {
		$this->groups = UserAccess::groups();
		
		if ($this->formValidate(['group']) && in_array(post('group'), $this->groups)) {
			$has_changes = Account::getRawGroup() != post('group');
			Session::setGroup($has_changes ? post('group') : null);
			Session::setGid($has_changes ? post('gid') : null);
		}
		
		$this->gids = reArray(User::getAllGroups(), 'gid', 'gid');
		$this->gid = Account::getGid();
		$this->view = 'admin/user/view-as';
	}
	
}

?>