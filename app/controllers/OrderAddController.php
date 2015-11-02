<?php

include_once '../core/interfaces/IRedirect.php';
include_once '../app/controllers/BaseOrderController.php';

class OrderAddController extends BaseOrderController implements IRedirect {
	
	public $page;
	public $oid;
	
	public function beforeRender() {
		$this->page = $this->createPage('order/OrderAdd');
		
		if ($this->add()) return;
		
		$this->page->photopolymers = View::convertSelect(GroupPhotopolymer::getAll(Account::getGid()), 'pid', 'name');
		
		if (count($this->page->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
			return;
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
		
		$this->page->commented = UserAccess::check(UserAccess::COMMENT_ADD);
	}
	
	public function getContent() {
		if (!is_null($this->oid)) {
			$this->pick('system/redirect');
		} else if (count($this->page->photopolymers)>0) {
			$this->page->getContent();
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	public function add() {
		if ($this->formValidate($this->page->fieldsMandatory())) {
			$fields = $this->page->fieldsAll();
			$values = $this->formValues($fields);
			
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