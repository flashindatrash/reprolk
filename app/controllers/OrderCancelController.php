<?php

include_once '../app/controllers/BaseOrderController.php';
include_once '../core/interfaces/IRedirect.php';
include_once '../core/interfaces/IConfirm.php';

class OrderCancelController extends BaseOrderController implements IRedirect, IConfirm {
	
	private $view;
	
	public function beforeRender() {
		$this->loadOrder();
		
		if (is_null($this->order)) return;
		
		if (!$this->order->canCancel()) {
			$this->addAlert(View::str('warning_order_cancel'), 'warning');
			return;
		} if ($this->formValidate([])) {
			if ($this->order->cancel()) {
				$this->view = 'system/redirect';
			} else {
				$this->addAlert(View::str('error_order_cancel'), 'danger');
			}
		} else {
			$this->view = 'system/confirm';
		}
	}
	
	public function getContent() {
		if (!is_null($this->view)) $this->pick($this->view);
	}
	
	public function getRedirect() {
		return new Redirect(View::str('order_cancel_successfuly'), Application::$routes->byName(Route::ORDER_VIEW)->path . '?id=' . $this->order->id); 
	}
	
	public function getConfirm() {
		return sprintf(View::str('you_sure_order_cancel'), $this->order->title);
	}
	
}

?>