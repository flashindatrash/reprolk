<?php

class BaseOrderController extends BaseController {
	
	public $order;
	
	protected function loadOrder() {
		if (!hasGet('id')) {
			$this->addAlert(View::str('error_order_not_found'), 'danger');
			return false;
		}
		
		$gid = null;
		if (Account::isAdmin() && Session::hasGid() || !Account::isAdmin()) {
			$gid = Account::getGid();
		}
		
		$id = get('id');
		$this->order = Order::byId($id, $gid);
		
		if (!$this->order) {
			$this->addAlert(View::str('error_order_not_found'), 'danger');
			return false;
		}
		
		return true;
	}
	
}