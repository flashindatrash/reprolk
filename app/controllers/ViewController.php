<?php

class ViewController extends BaseController {
	
	public function __construct($url) {
		initDataBase();
		print $url;
	}
	
}

?>