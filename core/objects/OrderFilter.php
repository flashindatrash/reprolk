<?php

class OrderFilter {
	
	public $status;
	public $date_due_start;
	public $date_due_end;
	public $date_created_start;
	public $date_created_end;
	
	public static function fields() {
		return ['status', 'date_due_start', 'date_due_end', 'date_created_start', 'date_created_end'];
	}
	
}

?>