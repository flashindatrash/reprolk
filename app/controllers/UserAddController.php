<?php

class UserAddController extends BaseController {
	
	public $groups;
	
	private $user_id = null;
	
	public function beforeRender() {
		$this->groups = UserAccess::groups();
		
		$fields = User::$fields_mandatory;
		$values = $this->formValidate($fields);
		
		if (!is_null($values)) {
			$this->user_id = User::add($fields, $values);
		}
	}
	
	public function getContent() {
		if (!is_null($this->user_id)) {
			$this->pick('system/success-save');
		}
		
		$this->pick('user/add');
	}
	
}

?>