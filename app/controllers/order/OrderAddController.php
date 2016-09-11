<?php

Util::inc('controllers', 'api/ApiOrderAddController.php');
Util::inc('controllers', 'order/BaseOrderController.php');
Util::inc('objects', 'order/TemplateStorage.php');
Util::inc('interfaces', 'IRedirect.php');

class OrderAddController extends BaseOrderController implements IRedirect {
	
	public $form;
	public $templates;
	public $api;
	
	public function beforeRender() {
		$this->api = new ApiOrderAddController();
		$this->templates = new TemplateStorage();
		
		$this->createOrderForm();
		
		if ($this->add()) {
			$this->setTemplate('empty');
			$this->view = 'system/redirect';
			return;
		}
		
		$this->include_datetimepicker();
		$this->include_fileinput();
		
		$this->view = 'order/add';
	}
	
	public function createOrderForm() {
		$this->form = $this->createForm('OrderAdd');
		$this->form->loadFields(Route::ORDER_ADD);
		//возмем значения из шаблона, если тот выбран
		if (!is_null($this->templates)) $this->form->setDefault($this->templates->getFieldsValue());
	}
	
	public function add() {
		if ($this->formValidate($this->form->fieldsMandatory())) {
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
		}
		
		return false;
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	protected function generateBreadcrump($items) {
		return parent::generateBreadcrump($items) . $this->templates->generateBreadcrump();
	}
	
}

?>