<?php

class Photopolymer extends BaseModel {
	
	public $id;
	public $name;
	
	public static function byId($id) {
		return Application::$db->selectRow('photopolymers', '*', '`id` = ' . $id, 'Photopolymer');
	}
	
	public static function getAll() {
		return Application::$db->selectRows('photopolymers', '*', '1', 'Photopolymer');
	}
	
}

?>