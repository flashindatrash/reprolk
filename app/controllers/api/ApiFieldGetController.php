<?php

Util::inc('controllers', 'api/BaseApiController.php');
Util::inc('objects', 'Form.php');

/*
params:
	page
	
response:
	fields[]
*/

class ApiFieldGetController extends BaseApiController {
	
	private $fields;
	
	public function execute() {
		$page = post('page');
		
		if (is_null(Application::$routes->byName($page))) {
			$this->addAlert(View::str('error_api_page_not_found'), 'danger');
			return false;
		}
		
		$form = new Form();
		$form->loadFields($page);
		
		//возмем все филды, которые canUse
		$this->fields = array();
		foreach ($form->fields as $field) {
			//возмем только те которые можно юзать
			if ($field->canUse()) {
				//добавим варианты заполнения
				if ($page==Route::ORDER_ADD && $field->name==Order::FIELD_PID) {
					//хардкор! 
					//1C: Недопустимое имя свойства: '9' для чтения JSON в объект Структура
					//ключом не может выступать число, ну что за бред
					$field->addProperty("option", GroupOption::getAll($field->id));
				} else {
					$field->addProperty("option", $field->getOption());
				}
				
				$this->fields[] = $field;
			}
		}
		
		return true;
	}
	
	public function responsed() {
		return array(
			'fields' => $this->fields
		);
	}
	
	public function getDefaultRequest() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey(),
			'page' => Route::ORDER_ADD
		);
	}
	
	public function createRequestForm() {
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		$form->addField('page', INPUT_TEXT, true);
		return $form;
	}
}

?>