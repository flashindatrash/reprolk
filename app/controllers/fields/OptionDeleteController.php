<?php

Util::inc('controllers', 'fields/BaseOptionController.php');
Util::inc('controllers', 'api/ApiOptionDeleteController.php');
Util::inc('interfaces', 'IConfirm.php');

class OptionDeleteController extends BaseOptionController implements IConfirm {
	
	private $api;
	private $option;
	
	public function beforeRender() {
		if (!$this->hasField()) return;
		
		if ($this->formValidate([])) {
			/*if ($this->option->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_option_delete'), 'danger');
			}*/
		} else {
			$this->view = 'system/confirm';
		}

		/*
		$this->api = new ApiOptionDeleteController();
		
		if ($this->api->checkRequest() && $this->api->execute()) {
			$this->view = 'system/redirect';
		} else {
			$this->view = 'admin/option/add';
		}
		
		$this->mergeAlerts($this->api);
	
		$this->form = $this->api->request;
		$this->form->hide(Auth::FIELD_KEY, Account::getAuthKey());
		$this->form->hide(Option::FIELD_FID, $this->fid);*/
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_option_delete'), $this->option->name);
	}

}

/*
Util::inc('controllers', 'base/WebController.php');
Util::inc('interfaces', 'IRedirect.php');
Util::inc('interfaces', 'IConfirm.php');

class OptionDeleteController extends WebController implements IRedirect, IConfirm {
	
	private $option;
	
	public function beforeRender() {
		$this->option = hasGet('id') ? Option::byId(get('id')) : null;
		
		if (is_null($this->option)) return;
		
		if ($this->formValidate([])) {
			if ($this->option->remove()) {
				$this->setTemplate('empty');
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_option_delete'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('option_delete_successfuly'), Application::$routes->byName(Route::OPTION_ALL)->path); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_option_delete'), $this->option->name);
	}

}
*/

?>
