<?php

class OrderFilter {
	
	public $status;
	public $statuses;
	public $search;
	public $username;
	public $date_due_start;
	public $date_due_end;
	public $date_created_start;
	public $date_created_end;
	
	public static function fields() {
		return ['status', 'search', 'date_due_start', 'date_due_end', 'date_created_start', 'date_created_end', 'username'];
	}
	
	public function getUsers() {
		$users = User::getAll(['id', 'username'], null, Account::getGid());
		$arr = reArray($users, 'id', 'username');
		$arr[0] = View::str('all_users');
		ksort($arr);
		return $arr;
	}
	
	public function getDefaultStatuses() {
		$statuses = Order::getStatuses();
		removeArrayItem(Order::CANCELED, $statuses);
		removeArrayItem(Order::FINISHED, $statuses);
		return $statuses;
	}
	
	public function getArchiveStatuses() {
		$statuses = array();
		$statuses[] = Order::CANCELED;
		$statuses[] = Order::FINISHED;
		return $statuses;
	}
	
	public function searchRegexp() {
		$regexp = $this->search;
		$regexp = str_replace('*', '.*', $regexp);
		return $regexp;
	}
	
}

?>