<?php

class UserAllController extends BaseController {
	
	public $users;
	public $groups;
	public $fields;
	public $currentGroup;
	
	public function beforeRender() {
		//поля для отображения
		$this->fields = array('id', 'username', 'email', 'group', 'gid');
		//весь список групп для отображения
		$this->groups = UserAccess::$permissions[UserAccess::AUTH];
		//текущая вкладка
		$this->currentGroup = in_array(get('group'), $this->groups) ? get('group') : null;
		//добавим псевдо-вкладку "ВСЕ"
		$this->groups[] = 'all';
		$this->users = User::getAll($this->fields, $this->currentGroup, Account::isAdmin() ? null : Account::getGid());
		$this->view = 'admin/user/all';
	}
	
}

?>