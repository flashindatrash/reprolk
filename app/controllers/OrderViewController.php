<?php

class OrderViewController extends BaseController {
	
	public $order;
	
	public function beforeRender() {
		if (!hasGet('id')) {
			$this->addError($this->str('error_order_not_found'));
			return;
		}
		
		$id = get('id');
		$this->order = Order::byId($id);
		
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