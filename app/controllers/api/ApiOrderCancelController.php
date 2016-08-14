<?php

Util::inc('controllers', 'api/BaseApiController.php');

/*
params:
	id
	
response:
	status
	
*/

class ApiOrderCancelController extends BaseApiController {
	
	private $order;
	
	public function execute() {
		$this->order = Order::byId(post(Order::FIELD_ID), Account::getGid());
		if (is_null($this->order)) {
			$this->addAlert(View::str('error_api_order_not_found'), 'danger');
			return false;
		}
		
		if (!$this->order->canCancel()) {
			$this->addAlert(sprintf(View::str('error_api_order_can_not_be_canceled'), $this->order->status), 'danger');
			return false;
		}
		
		if ($this->order->cancel()) {
			return true;
		}
		
		return false;
	}
	
	public function getDefaultRequest() {
		return array(
			Auth::FIELD_KEY => Account::getAuthKey(),
			Order::FIELD_ID => 8305
		);
	}
	
	public function createRequestForm() {
		$form = parent::createRequestForm();
		$form->addField(Auth::FIELD_KEY, INPUT_TEXT, false);
		$form->addField(Order::FIELD_ID, INPUT_TEXT, true);
		return $form;
	}
	
}

?>