<?php

Util::inc('controllers', 'order/OrderAllController.php');

class OrderArchiveController extends OrderAllController {
	
	protected function applyFillter() {
		$this->order_filter->statuses = $this->order_filter->getArchiveStatuses();
		if (is_null($this->order_filter->status)) {
			$this->order_filter->status = $this->order_filter->statuses;
		}
	}
	
}

?>