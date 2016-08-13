<?php

class UserHistoryController extends BaseController {
	
	public $history;
	
	public function beforeRender() {
		$this->history = UserHistory::getAll();
		
		$this->addCSSfile('selected.table');
		$this->addJSfile('selected.table');
		
		$this->view = 'user/history';
	}
	
}

?>