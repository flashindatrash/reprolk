<?php

Util::inc('controllers', 'BaseOrderController.php');
Util::inc('interfaces', 'IRedirect.php');

class OrderApprovedController extends BaseOrderController implements IRedirect {
	
	public function beforeRender() {
		if (!$this->loadOrder()) return;
		
		if ($this->approve()) {
			$this->setTemplate('empty');
			$this->view = 'system/redirect';
			return;
		}
		
		$this->addAlert(View::str('warning_order_approval'), 'warning');
	}
	
	//IRedirect
	public function getRedirect() {
		return new Redirect(View::str('order_approved_successfuly'), Application::$routes->byName(Route::ORDER_ALL)->path);
	}
	
	public function approve() {
		return $this->order->approve() && $this->save();
	}
}

?>