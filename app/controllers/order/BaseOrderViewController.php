<?php

Util::inc('controllers', 'order/BaseOrderController.php');

class BaseOrderViewController extends BaseOrderController {
	
	public $form;
	
	public function createForm($name) {
		$form = parent::createForm($name);
		$form->loadFields(Route::ORDER_ADD);
		$form->setSession(objectToArray($this->order));
		$form->setSession(array('pid' => $this->order->photopolymer_name, 'username' => View::link(Route::PROFILE, $this->order->username, 'id=' . $this->order->uid)));
		return $form;
	}
}

?>