<?php

class ViewController extends BaseController {
	
	public function __construct($url) {
		parent::__construct();
		print $url;
	}
	
}

?>