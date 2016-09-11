<?php

Util::inc('controllers', 'base/WebController.php');
Util::inc('controllers', 'fields/BaseOptionController.php');

class OptionController extends BaseOptionController {
	
	public $options;
	
	public function beforeRender() {
		if (!$this->hasField()) return;
		
		$this->options = Option::getAll($this->fid);
		$this->view = 'admin/option/index';
	}
	
}

?>