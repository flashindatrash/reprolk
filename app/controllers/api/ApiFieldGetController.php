<?php

include_once '../app/controllers/api/BaseApiController.php';
include_once '../core/objects/Form.php';

/*
params:
	page
	
response:
	fields[]
*/

class ApiFieldGetController extends BaseApiController {
	
	private $page;
	private $form;
	private $fields;
	
	public function processingApi() {
		if (!hasPost('page')) {
			$this->addAlert(View::str('error_api_null_page'), 'danger');
			return;
		}
		
		$this->page = post('page');
		if (is_null(Application::$routes->byName($this->page))) {
			$this->addAlert(View::str('error_api_page_not_found'), 'danger');
			return;
		}
		
		$this->success = true;
		
		$this->form = new Form();
		$this->form->loadFields($this->page);
		
		//возмем все филды, которые canUse
		$this->fields = array();
		foreach ($this->form->fields as $field) {
			//возмем только те которые можно юзать
			if ($field->canUse()) {
				//добавим варианты заполнения
				if ($this->page==Route::ORDER_ADD && $field->name==Order::FIELD_PID) {
					//хардкор! 
					//1C: Недопустимое имя свойства: '9' для чтения JSON в объект Структура
					//ключом не может выступать число, ну что за бред
					$field->addProperty("option", GroupPhotopolymer::getAll());
				} else {
					$field->addProperty("option", $field->getOption());
				}
				
				$this->fields[] = $field;
			}
		}
		
		$this->response["fields"] = $this->fields;
	}
	
}

?>