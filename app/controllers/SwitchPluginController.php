<?php

include_once '../app/controllers/JSONController.php';

class SwitchPluginController extends JSONController {
	
	public function beforeRender() {
		parent::beforeRender();
		
		if (!hasGet('name')) {
			addAlert(View::str('error_unkhnow_plugin'), 'danger');
			return;
		}
		
		$name = get('name');
		$enabled = toBool(get('enabled'));
		
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