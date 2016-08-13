<?php

Util::inc('controllers', 'base/WebController.php');

class UserController extends WebController {
	
	public $fields;
	public $user;
	public $isMe = false;
	public $plugins_all;
	public $plugins_enabled;
	
	public function beforeRender() {
		$this->fields = array('id', 'email', 'group', 'gid');
		$this->user = hasGet('id') ? User::byId(get('id')) : Application::$user;
		if (is_null($this->user)) {
			$this->addAlert(sprintf(View::str('not_found'), View::str('user')), 'warning');
		} else {
			$this->isMe = $this->user->id==Account::getId();
			if ($this->isMe) {
				$this->plugins_all = reArray(Plugin::getAll(), null, 'name');
				$this->plugins_enabled = reArray(UserPlugin::getAll(Account::getId()), null, 'name');
			}
		}
	}
	
	public function getContent() {
		if (is_null($this->user)) return;
		
		$this->pick('user/index');
		if ($this->isMe) {
			$this->pick('user/plugins');
		}
	}
	
}

?>