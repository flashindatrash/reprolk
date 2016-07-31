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
		$this->response["FILES"] = isset($_FILES['files']) ? count($_FILES['files']) : 0;
		//$this->response["headers"] = getallheaders();
		//$this->response["payload"] = file_get_contents('php://input');
	}
	
}

?>