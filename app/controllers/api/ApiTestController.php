<?php

include_once '../app/controllers/api/BaseApiController.php';

/*
params:
	fields
	
response:
	order
*/

class ApiTestController extends BaseApiController {
	
	public function processingApi() {
		$this->success = true;
		$this->response["POST"] = $_POST;
		$this->response["FILES"] = isset($_FILES['files']) ? $_FILES['files'] : [];
	}
	
}

?>