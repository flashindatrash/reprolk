<?php

class UserAddController extends BaseController {
	
	public $groups;
	
	private $user_id = null;
	private $values = array();
	
	public function beforeRender() {
		$this->groups = UserAccess::groups();
		
		if ($this->validate()) {
			$this->user_id = User::add(User::$fields_mandatory, $this->values);
		}
	}
	
	public function getContent() {
		if (!is_null($this->user_id)) {
			$this->pick('system/success-save');
		}
		
		$this->pick('user/add');
	}
	
	private function validate() {
		if (post('send')!='1') return false;
		
		$valid = true;
		
		foreach (User::$fields_mandatory as $field) {
			if (!hasPost($field)) {
				$valid = false;
				$this->addError(sprintf($this->str('must_enter'), $this->str($field)));
			} else {
				$this->values[] = post($field);
			}
		}
		
		return $valid;
	}
	
}

?>