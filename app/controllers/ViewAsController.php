<?php

class ViewAsController extends BaseController {
	
	public $groups;
	
	public function beforeRender() {
		$this->groups = UserAccess::groups();
		
		if ($this->validate()) {
			$group = post('group');
			$user_group = Application::$user->group;
			SystemSession::setGroup($user_group == $group ? null : $group);
		}
	}
	
	public function getContent() {
		$this->pick('admin/view-as');
	}
	
	private function validate() {
		return post('send')=='1' && hasPost('group') && in_array(post('group'), $this->groups);
	}
	
}

?>