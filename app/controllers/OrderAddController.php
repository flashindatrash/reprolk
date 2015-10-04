<?php

include_once '../core/interfaces/IRedirect.php';

class OrderAddController extends BaseController implements IRedirect {
	
	public $photopolymers;
	public $oid;
	public $commented;
	
	public function beforeRender() {
		if ($this->add()) return;
		
		$this->photopolymers = View::convertSelect(GroupPhotopolymer::getAll(Account::getGid()), 'pid', 'name');
		
		if (count($this->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
			return;
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
		
		$this->commented = UserAccess::check(UserAccess::COMMENT_ADD);
	}
	
	public function getContent() {
		if (!is_null($this->oid)) {
			$this->pick('system/redirect');
		} else if (count($this->photopolymers)>0) {
			$this->pick('order/add');
		}
	}
	
	public function getRedirect() {
		return new Redirect(View::str('order_successfuly'), Application::$routes->byName(Route::ORDER_VIEW)->path . '?id=' . $this->oid, 2000);
	}
	
	public function add() {
		$fields = array('title', 'date_due', 'pid');
		
		if ($this->formValidate($fields)) {
			$values = $this->formValues($fields);
			
			$fields[] = 'uid';
			$values[] = Account::getId();
			
			$fields[] = 'urgent';
			$values[] = checkbox2bool(post('urgent'));
			
			$fields[] = 'date_created';
			$values[] = date('Y-m-d');
			
			$fields[] = 'date_changed';
			$values[] = date('Y-m-d');
			
			$this->oid = Order::add($fields, $values);
			
			if (!is_null($this->oid)) {
				
				if (hasPost('comment')) {
					Comment::add($this->oid, post('comment'));
				}
				
				return true;
			}
		}
		
		return false;
	}
	
}

?>