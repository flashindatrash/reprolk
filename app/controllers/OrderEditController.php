<?php

include '../app/controllers/BaseOrderController.php';

class OrderEditController extends BaseOrderController {
	
	public $photopolymers;
	
	public function beforeRender() {
		$this->loadOrder();
		
		if (!is_null($this->order) && $this->edit()) {
			$this->addAlert(View::str('success_save'), 'success');
		}
		
		$this->photopolymers = View::convertSelect(GroupPhotopolymer::getAll(Account::getGid()), 'pid', 'name');
		if (count($this->photopolymers)==0)  {
			$this->addAlert(View::str('error_not_have_photopolymer'), 'warning');
		}
		
		$this->addJSfile('datetimepicker.min');
		$this->addCSSfile('datetimepicker');
	}
	
	public function getContent() {
		if ($this->order) $this->pick('order/edit');
	}
	
	private function edit() {
		$fields = array('title', 'date_due', 'pid');
		
		if ($this->formValidate($fields)) {
			$values = $this->formValues($fields);
			
			$fields[] = 'urgent';
			$fields[] = 'date_changed';
			
			$values[] = checkbox2bool(post('urgent'));
			$values[] = date('Y-m-d');
			
			if ($this->order->edit($fields, $values)) return true;
		}
		
		return false;
	}
	
}

?>