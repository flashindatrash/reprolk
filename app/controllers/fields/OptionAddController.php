<?php

Util::inc('controllers', 'fields/BaseOptionController.php');
Util::inc('controllers', 'api/ApiOptionAddController.php');

class OptionAddController extends BaseOptionController {
	
	public $api;
	public $form;

	public function beforeRender() {
		if (!$this->hasField()) return;
		
		$this->api = new ApiOptionAddController();
		
		if ($this->api->checkRequest() && $this->api->execute()) {
			$this->view = 'system/redirect';
		} else {
			$this->view = 'admin/option/add';
		}
		
		$this->mergeAlerts($this->api);
	
		$this->form = $this->api->request;
		$this->form->hide(Auth::FIELD_KEY, Account::getAuthKey());
		$this->form->hide(Option::FIELD_FID, $this->fid);
	}
	
}

?>