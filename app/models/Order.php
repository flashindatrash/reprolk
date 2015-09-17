<?php

class Order extends BaseModel {
	
	public $id;
	public $user;
	public $title;
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
	
	public static function getAll($fields, $gid = null) {
		$table_users = Application::$db->tableName('users');
		$table_orders = Application::$db->tableName('orders');
		
		$fields = DataBaseManager::array2fields($fields);
		$fields .= ', `user`';
		$fields .= ', ' . $table_orders . '.id as id';
		$fields .= ', ' . $table_users . '.username as username';
		
		$join = $table_users . ' on ' . $table_orders . '.user=' . $table_users . '.id';
		
		$where = is_null($gid) ? '1' : $table_users . '.gid = ' . $gid;
		
		return Application::$db->selectRows('orders', $fields, $where, 'Order', '0, 300', $join);
	}
	
	public static function byId($id) {
		return Application::$db->selectRow('orders', '*', '`id` = ' . $id, 'Order');
	}
}

?>