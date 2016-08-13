<?php

Util::inc('controllers', 'OrderDuplicateController.php');

class OrderRepeatController extends OrderDuplicateController {
	
	const MANDATORY = array('date_due');
	const EDITABLE = array('date_due', 'urgent', 'comment');
	
	private $root;
	
	public function beforeRender() {
		parent::beforeRender();
		
		if (is_null($this->root)) {
			$this->view = null;
		}
	}
	
	public function createOrderForm() {
		$this->order->title = sprintf(View::str('order_title_repeat'), $this->order->title);
		
		parent::createOrderForm();
		
		$this->form->setSession(array('pid' => $this->order->photopolymer_name));
		
		//перезапишем полям обязательно для заполнения
		foreach ($this->form->fields as $field) {
			$field->mandatory = in_array($field->name, self::MANDATORY);
		}
	}
	
	protected function generateTemplate() {
		$this->template_id = 0;
		$this->template_fields = [];
	}
	
	public function add() {
		//перед добавлением запишем рутовый заказ
		$this->root = $this->order;
		
		if (!is_null($this->root) && !$this->root->canRepeat()) {
			$this->addAlert(View::str('error_order_cannot_repeat'), 'danger');
			$this->root = null;
			return false;
		}
		return parent::add();
	}
	
	protected function save($files = null, $comment_id = 0) {
		$this->order->addProperty("repeat", array(
			"id" => $this->root->id,
			"id_1c" => $this->root->id_1c
		));
		return parent::save($files, $comment_id);
	}
	
	public function formValues($fields) {
		$values = array();
		
		if (is_null($this->order)) return $values;
		
		foreach ($fields as $field) {
			switch ($field) {
				//некоторым значениям присвоим дефолтное значение
				case 'status':
					$values[] = Order::INCOMING;
					break;
				case 'id_1c':
				case 'number_1c':
					$values[] = 0;
					break;
				default:
					if (in_array($field, self::EDITABLE)) {
						$values[] = post($field);
					} else {
						//если поля не обязательны, возмем значения из заказа
						$values[] = $this->order->$field;
					}
			}
			
		}
		return $values;
	}
	
	protected function getFormName() {
		return 'OrderRepeat';
	}
	
}

?>