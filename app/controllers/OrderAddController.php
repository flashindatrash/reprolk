<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../app/controllers/BaseOrderController.php';

class OrderAddController extends BaseOrderController implements IRedirect {
	
	public $form;
	public $photopolymers;
	
	public function beforeRender() {
		$this->photopolymers = reArray(GroupPhotopolymer::getAll(Account::getGid()), 'pid', 'name');
		
		$this->createOrderForm();
		
		if ($this->add()) {
			$this->view = 'system/redirect';
			return;
		}
		
		if (count($this->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
			return;
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
		$this->view = 'order/add';
	}
	
	public function createOrderForm() {
		$this->form = $this->createForm('Order');
		$this->form->loadFields(Route::ORDER_ADD);
		$this->form->setValue(array('pid' => $this->photopolymers));
		$this->form->setSession(array('date_due' => (new DateTime('tomorrow'))->format("Y-m-d")));
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	public function add() {
		if ($this->formValidate($this->form->fieldsMandatory())) {
			$fields = $this->form->fieldsAll();
			$values = $this->formValues($fields);
			
			$oid = Order::add($fields, $values);
			
			if (!is_null($oid)) {
				
				$loaded_order = $this->loadOrder($oid);
				if (!$loaded_order) return false;
				
				//добавляем коммент
				if (hasPost('comment')) {
					Comment::add($oid, post('comment'), $this->order->status);
				}
				
				if ($this->save($_FILES['files'])) {
					return true;
				} else {
					$this->order->remove(true);
				}
			}
		}
		
		return false;
	}
	
}

?>