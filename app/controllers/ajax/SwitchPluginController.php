<?php

Util::inc('controllers', 'base/JSONController.php');

class SwitchPluginController extends JSONController {
	
	public function beforeRender() {
		parent::beforeRender();
		
		if (!hasPost('name')) {
			$this->addAlert(View::str('error_unkhnow_plugin'), 'danger');
			return;
		}
		
		$name = post('name');
		$enabled = toBool(post('enabled'));
		
		if (!$enabled) {
			$this->response['action'] = 'delete';
			$this->success = UserPlugin::deleteByName($name, Account::getId());
		} else {
			$this->response['action'] = 'add';
			$this->success = !is_null(UserPlugin::add($name, Account::getId()));
		}
		
	}
	
}

?>