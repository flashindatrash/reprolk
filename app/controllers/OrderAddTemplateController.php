<?php

include_once '../app/controllers/OrderAddController.php';

class OrderAddTemplateController extends OrderAddController {
	
	public function createOrderForm() {
		parent::createOrderForm();
		$this->addAlert("что-то случилось :(", 'danger');
		//$template = GroupTemplate::getAll(Account::getGid());
		//$this->form->setSession(reArray($template, 'name', 'value'));
	}
	
}

?>