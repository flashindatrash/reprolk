<?php

class Order extends BaseModel {
	
	public $id;
	public $jobname;
	public $jobid1c;
	
	public static function getAll($fields = '*') {
		if (is_array($fields)) $fields = DataBaseManager::array2fields($fields);
		return Application::$db->selectRows('orders', $fields, '1', 'Order', '0, 300');
	}
}

?>