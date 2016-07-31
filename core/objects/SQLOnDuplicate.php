<?php

class SQLOnDuplicate {
	
	//все столбцы
	private $fields = [];
	
	public function __construct() {
		
	}
	
	public function addField($field) {
		$this->fields[] = $field . ' = VALUES(' . $field . ')';
	}
	
	public function toString() {
		if (count($this->fields)==0) return '';
		return 'on duplicate key update ' . join(', ', $this->fields);
	}
}

?>