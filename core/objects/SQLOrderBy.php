<?php

class SQLOrderBy {
	
	public $field;
	public $by;
	
	public function __construct($field, $by = 'desc') {
		$this->field = $field;
		$this->by = in_array($by, ['desc', 'asc']) ? $by : 'desc';
	}
	
	public function toString() {
		return 'order by ' . $this->field . ' ' . $this->by;
	}
	
}


?>