<?php

Util::inc('controllers', 'order/BaseOrderController.php');

class OrderApprovalController extends BaseOrderController {
	
	public $revisions;
	public $revisions_path;
	public $route_approved;
	public $route_disapproved;
	
	public function beforeRender() {
		if (!$this->loadOrder()) return;
		
		if (!$this->order->canApprove()) {
			$this->addAlert(View::str('warning_order_approval'), 'warning');
			return;
		}
		
		$this->route_approved = Application::$routes->byName(Route::ORDER_APPROVED);
		$this->route_disapproved = Application::$routes->byName(Route::ORDER_DISAPPROVED);
		
		$this->revisions = Revision::getAll($this->order->id, new SQLOrderBy('rid'));
		
		$this->addCSSfile('controller/OrderApproval');
		$this->addJSfile('controller/OrderApproval');
		$this->revisions_path = Application::$config['public']['revisions'];
		
		$this->view = 'order/approval';
	}
	
	public function getContent() {
		if (!is_null($this->view)) {
			$this->pick('order/disapproved');
			$this->pick($this->view);
		}
	}
	
}

?>