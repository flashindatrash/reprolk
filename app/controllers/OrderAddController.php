<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../app/controllers/BaseOrderController.php';

class OrderAddController extends BaseOrderController implements IRedirect {
	
	public $form;
	public $oid;
	public $photopolymers;
	public $commented;
	
	public function beforeRender() {
		$this->photopolymers = View::convertSelect(GroupPhotopolymer::getAll(Account::getGid()), 'pid', 'name');
		$this->commented = UserAccess::check(UserAccess::COMMENT_ADD);
		
		$this->form = $this->createForm('Order');
		$this->form->loadFields(Route::ORDER_ADD);
		$this->form->setValue(array('pid' => $this->photopolymers));
		
		if ($this->add()) return;
		
		if (count($this->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
			return;
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
	}
	
	public function getContent() {
		if (!is_null($this->oid)) {
			$this->pick('system/redirect');
		} else if (count($this->photopolymers)>0) {
			$this->pick('order/add');
		}
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	public function add() {
		if ($this->formValidate($this->form->fieldsMandatory())) {
			$fields = $this->form->fieldsAll();
			$values = $this->formValues($fields);
			
			return false;
			$this->oid = Order::add($fields, $values);
			
			if (!is_null($this->oid)) {
				
				//добавляем коммент
				if (hasPost('comment')) {
					Comment::add($this->oid, post('comment'));
				}
				
				return $this->loadOrder($this->oid) && $this->save($_FILES['files']);
			}
		}
		
		return false;
	}
	
}

?>