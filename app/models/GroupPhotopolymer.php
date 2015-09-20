<?php

class GroupPhotopolymer extends BaseModel {
	
	public $gid;
	public $pid;
	public $name;
	
	public static $fields = array('gid', 'pid');
	
	public static function getAll($gid) {
		$table_group_photopolymers = Application::$db->tableName('group_photopolymers');
		$table_photopolymers = Application::$db->tableName('photopolymers');
		
		$fields = '`gid`, `pid`';
		$fields .= ', ' . $table_photopolymers . '.name as name';
		
		$join = $table_photopolymers . ' on ' . $table_group_photopolymers . '.pid=' . $table_photopolymers . '.id';
		
		$where = '`gid` = ' . $gid;
		
		return Application::$db->selectRows('group_photopolymers', $fields, $where, 'GroupPhotopolymer', '0, 300', $join);
	}
	
	public static function set($group, $photopolymers) {
		$values = array();
		foreach ($photopolymers as $photopolymer) {
			$values[] = array($group, $photopolymer);
		}
		
		$delete = Application::$db->delete('group_photopolymers', '`gid` = ' . $group);
		$insert = Application::$db->insertRows('group_photopolymers', self::$fields, $values);
		return $delete && !is_null($insert);
	}
	
}

?>