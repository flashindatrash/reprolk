<?php

class Order extends BaseModel {
	
	public $id;
	public $user;
	public $name;
	public $id_1c;
	public $number_1c;
	public $status;
	public $photopolymer;
	public $form_count;
	public $area;
	public $urgent;
	public $angels;
	public $colorproof;
	public $date_created;
	public $date_changed;
	public $date_due;
	
	public static $fields_table_select = array('id', 'name', 'status', 'date_due');
	public static $fields_table_view = array('name', 'status', 'date_due');
	
	public static function getAll($fields = '*') {
		if (is_array($fields)) $fields = DataBaseManager::array2fields($fields);
		return Application::$db->selectRows('orders', $fields, '1', 'Order', '0, 300');
	}
}

?>