<?php

Util::inc('controllers', 'api/BaseApiController.php');

class ApiOptionDeleteController extends BaseApiController {
	
	private $option;
	
	public function execute() {
		$this->option = Option::byId(post(Option::FIELD_ID));
		
		if (is_null($this->option)) {
			$this->addAlert(View::str('error_api_unknown_option'), 'danger');
			return false;
		}

		//если включен безопасный режим, или опция действительно удалена
		if ($this->isSafeMode || $this->option->remove()) {
			return true;
		}

		return false;
	}
	
	public function responsed() {
		return array(
			'id' => $this->option->id
		);
	}
	
	public function getDefaultRequest() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey(),
			Option::FIELD_ID => 0
		);
	}
	
	public function createRequestForm() {
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		$form->addField(Option::FIELD_ID, INPUT_NUMBER, true);
		return $form;
	}
	
}

?>