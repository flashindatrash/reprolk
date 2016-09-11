<?php

Util::inc('controllers', 'api/BaseApiController.php');

class ApiOptionAddController extends BaseApiController {
	
	private $field;
	private $option;
	
	public function execute() {
		$this->option = new Option();
		$this->option->fid = post(Option::FIELD_FID);
		$this->option->name = post(Option::FIELD_NAME);
		$this->option->id_1c = post(Option::FIELD_ID_1C);
		
		$this->field = Field::byId($this->option->fid);
		
		if (is_null($this->field)) {
			$this->addAlert(View::str('error_api_unknown_field'), 'danger');
			return false;
		}

		if (!$this->field->isCustomized()) {
			$this->addAlert(View::str('error_api_field_is_not_customized'), 'danger');
			return false;
		}
		
		//если включен безопасный режим, или опция действительно добавлена
		if ($this->isSafeMode || $this->option->save()) {
			return true;
		}

		return false;
	}
	
	public function responsed() {
		return array(
			'option' => $this->option
		);
	}
	
	public function getDefaultRequest() {
		$fields = Field::getCustomized();
		return array(
			Auth::FIELD_KEY => Account::getAuthKey(),
			Option::FIELD_FID => count($fields)>0 ? $fields[0]->id : 0,
			Option::FIELD_NAME => View::str('option_name')
		);
	}
	
	public function createRequestForm() {
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		$form->addField(Option::FIELD_FID, INPUT_NUMBER, true);
		$form->addField(Option::FIELD_NAME, INPUT_TEXT, true);
		$form->addField(Option::FIELD_ID_1C, INPUT_TEXT, false);
		return $form;
	}
	
}

?>