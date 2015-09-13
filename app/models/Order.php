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
	
	public static function getAll($fields) {
		$table_users = Application::$db->tableName('users');
		$table_orders = Application::$db->tableName('orders');
		
		$fields = DataBaseManager::array2fields($fields);
		$fields .= ', `user`';
		$fields .= ', ' . $table_orders . '.id as id';
		$fields .= ', ' . $table_users . '.username as username';
		
		$join = $table_users . ' on ' . $table_orders . '.user=' . $table_users . '.id';
		
		return Application::$db->selectRows('orders', $fields, '1', 'Order', '0, 300', $join);
	}
	
	public static function byId($id) {
		return Application::$db->selectRow('orders', '*', '`id` = ' . $id, 'Order');
	}
}

?>