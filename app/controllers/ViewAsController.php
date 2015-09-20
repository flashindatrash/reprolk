<?php

class ViewAsController extends BaseController {
	
	public $groups;
	public $gid;
	
	public function beforeRender() {
		$this->groups = UserAccess::groups();
		
		if ($this->validate()) {
			$has_changes = Account::getRawGroup() != post('group');
			
			Session::setGroup($has_changes ? post('group') : null);
			Session::setGid($has_changes ? post('gid') : null);
		}
		
		$this->gid = Account::getGid();
	}
	
	public function getContent() {
		$this->pick('admin/view-as');
	}
	
	private function validate() {
		return post('send')=='1' && hasPost('group') && in_array(post('group'), $this->groups);
	}
	
}

?>