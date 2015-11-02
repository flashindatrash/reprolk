<?php

class SQLOrderBy {
	
	public $field;
	public $by;
	public $group;
	
	public function __construct($field, $by = 'desc', $group = null) {
		$this->field = $field;
		$this->by = in_array($by, ['desc', 'asc']) ? $by : 'desc';
		$this->group = !is_null($group) ? 'group by `' . $group . '` ' : '';
	}
	
	public function toString() {
		return $this->group . 'order by ' . $this->field . ' ' . $this->by;
	}
	
}


?>