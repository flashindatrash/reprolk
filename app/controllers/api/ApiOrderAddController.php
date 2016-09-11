<?php

Util::inc('controllers', 'api/BaseApiOrderController.php');

class ApiOrderAddController extends BaseApiOrderController {
	
	public $form;
	
	public function execute() {
		$fields = $this->form->fieldsAll();
		$values = $this->formValues($fields);
		
		$oid = Order::add($fields, $values);
		
		if (!is_null($oid)) {
			if (!$this->loadOrder($oid)) return false;
			
			//добавляем коммент
			if (hasPost(Order::FIELD_COMMENT)) {
				Comment::add($oid, post(Order::FIELD_COMMENT), $this->order->status);
			}
			
			if ($this->save(isset($_FILES['files']) ? $_FILES['files'] : null)) {
				return true;
			} else {
				$this->order->remove(true);
			}
		}
		
		return false;
	}
	
	public function responsed() {
		return array(
			'order' => $this->order
		);
	}
	
	public function getDefaultRequest() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey()
		);
	}
	
	public function createRequestForm() {
		//создадим форму реквеста
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		
		//создадим форму создания заказа
		$this->form = $this->createForm('OrderAdd');
		$this->form->loadFields(Route::ORDER_ADD);
		
		//смержим две формы
		$form->fields = array_merge($form->fields, $this->form->fields);
		
		return $form;
	}
	
}

?>