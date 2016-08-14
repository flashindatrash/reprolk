<?php

//форма фильтра заказа

Util::inc('objects', 'Form.php');

class OrderFilterForm extends Form {
	
	public function loadFields($route, $gid = null) {
		parent::loadFields($route, $gid);
		
		$this->setDefault([
			'status' => $this->getStatuses()
		]);
		
		$this->setOption([
			'username' => $this->getUsers(),
			'status' => $this->getStatuses()
		]);
	}
	
	//кастомный рендер полей
	public function field_status($field) {
		return View::formNormal($field->name, $field->type, $field->getOption(), false, false, $field->getValue());
	}
	
	public function field_username($field) {
		return View::formNormal($field->name, $field->type, $field->getOption(), true, false, $field->getValue());
	}
	
	public function field_date_due($field) {
		return View::formNormal($field->name, $field->type, $field->getValue(), false);
	}
	
	public function field_date_created($field) {
		return View::formNormal($field->name, $field->type, $field->getValue(), false);
	}
	
	//получить всех юзеров для селекта
	private function getUsers() {
		$users = User::getAll([User::FIELD_ID, User::FIELD_USERNAME], null, $this->gid);
		$arr = reArray($users, User::FIELD_ID, User::FIELD_USERNAME);
		$arr[0] = View::str('all_users');
		ksort($arr);
		return $arr;
	}
	
	//получить все статусы для селекта
	private function getStatuses() {
		switch (Application::$routes->current->name) {
			case Route::ORDER_ALL:
				$statuses = Order::getStatuses();
				removeArrayItem(Order::CANCELED, $statuses);
				removeArrayItem(Order::FINISHED, $statuses);
				return $statuses;
			break;
			case Route::ORDER_ARCHIVE:
				$statuses = array();
				$statuses[] = Order::CANCELED;
				$statuses[] = Order::FINISHED;
				return $statuses;
			break;
			default:
				return Order::getStatuses();
		}
	}
}

?>