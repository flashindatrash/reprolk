<?php

class SQLOrderBy {
	
	const ASC = 'asc';
	const DESC = 'desc';
	
	//главаная сортировка, могут быть и дополнительные
	public $field;
	public $by;
	
	public $group;
	
	//все сортировки
	private $orders = [];
	
	public function __construct($field, $by = 'desc', $group = null) {
		$this->field = $field;
		$this->by = $this->fixBy($by);
		$this->group = !is_null($group) ? 'group by `' . $group . '` ' : '';
		$this->addOrder($this->field, $this->by);
	}
	
	public function addOrder($field, $by) {
		$this->orders[] = $field . ' ' . $by;
	}
	
	public function toString() {
		return $this->group . 'order by ' . join(', ', $this->orders);
	}
	
	private function fixBy($by) {
		return in_array($by, [SQLOrderBy::DESC, SQLOrderBy::ASC]) ? $by : 'desc';
	}
	
}


?>