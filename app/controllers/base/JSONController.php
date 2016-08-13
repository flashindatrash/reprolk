<?php

class JSONController extends BaseController {
	
	public $response = array();
	public $success = false;
	
	public function beforeRender() {
		$this->setTemplate('json');
	}
	
	public function getContent() {
		$r = [];
		
		$errors = array_merge($this->alerts['danger'], $this->alerts['warning']);
		$info = array_merge($this->alerts['info'], $this->alerts['success']);
		
		if (count($errors)>0) {
			$r['errors'] = $errors;
		}
		
		if (count($info)>0) {
			$r['info'] = $info;
		}
		
		$r['response'] = $this->response;
		$r['success'] = $this->success;
		return json_encode($r, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	}
	
}

?>