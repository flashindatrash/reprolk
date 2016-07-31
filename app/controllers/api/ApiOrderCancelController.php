<?php

include_once '../app/controllers/api/BaseApiController.php';

/*
params:
	id
	
response:
	status
	
*/

class ApiOrderCancelController extends BaseApiController {
	
	private $order;
	
	public function processingApi() {
		if (!hasPost('id')) {
			$this->addAlert(View::str('error_api_null_id'), 'danger');
			return;
		}
		
		$this->order = Order::byId(get('id'), Account::getGid());
		if (is_null($this->order)) {
			$this->addAlert(View::str('error_api_order_not_found'), 'danger');
			return;
		}
		
		if (!$this->order->canCancel()) {
			$this->response['status'] = $this->order->status;
			$this->addAlert(View::str('error_api_order_can_not_be_canceled'), 'danger');
			return;
		}
		
		if ($this->order->cancel()) {
			$this->success = true;
		}
	}
	
}

?>