<?php

class OrderViewController extends BaseController {
	
	public $order;
	
	public function beforeRender() {
		if (!hasGet('id')) {
			$this->addError($this->str('error_order_not_found'));
			return;
		}
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		$id = get('id');
		$this->order = Order::byId($id, $gid);
		
		if (!$this->order) {
			$this->addError($this->str('error_order_not_found'));
			return;
		}
	}
	
	public function getContent() {
		if ($this->order) $this->pick('order/view');
	}
	
}

?>