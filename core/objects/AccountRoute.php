<?php

class AccountRoute extends Route {
	
	public function linkText() {
		return Account::getName();
	}
	
	public function breadcrumpText() {
		return View::str('profile');
	}
	
}

?>