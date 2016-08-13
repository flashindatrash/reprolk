<?php

Util::inc('controllers', 'api/BaseApiController.php');
Util::inc('controllers', 'OrderAllController.php');
Util::inc('objects', 'OrderFilter.php');

/*
params:
	
	
response:
	orders[]
	
*/

class ApiOrderAllController extends BaseApiController {
	
	const FIELDS_SQL = array(
							Order::FIELD_TITLE, 
							Order::FIELD_RASTER_LINE, 
							Order::FIELD_STATUS, 
							Order::FIELD_DATE_DUE, 
							Order::FIELD_URGENT, 
							Order::FIELD_1C_ID, 
							Order::FIELD_1C_NUMBER,
							Order::FIELD_DATE_CREATED,
							Order::FIELD_DATE_CREATED
							);
	
	public function processingApi() {
		$this->success = true;
		
		$filter = new OrderFilter();
		$by = new SQLOrderBy(Order::FIELD_DATE_CREATED);
		
		//фильтр из POST'a
		foreach (OrderFilter::fields() as $field) {
			$filter->$field = hasPost($field) ? post($field) : null;
		}
		
		$this->response["orders"] = OrderAllController::loadOrders(self::FIELDS_SQL, $filter, $by, '0, 300');
	}
	
}

?>