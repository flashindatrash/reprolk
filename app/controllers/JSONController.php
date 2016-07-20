<?php

class JSONController extends BaseController {
	
	public $response = array();
	public $success = false;
	
	public function beforeRender() {
		$this->setTemplate('json');
	}
	
	public function getContent() {
		$r = $this->alerts;
		
		foreach (['danger', 'warning', 'info'] as $type) {
			if (count($r[$type])==0) unset($r[$type]);
		}
		
		$r['response'] = $this->response;
		$r['success'] = $this->success;
		return json_encode($r);
	}
	
}

?>