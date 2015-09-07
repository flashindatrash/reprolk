<?php

class Order extends BaseModel {
	
	public $id;
	public $jobname;
	public $jobid1c;
	
	public static $fields_view = array('id', 'jobname', 'jobid1c');
	
	public static function getAll() {
		return Application::$db->selectRows('orders', DataBaseManager::array2fields(self::$fields_view), '1', 'Order', '0, 300');
	}
}

?>