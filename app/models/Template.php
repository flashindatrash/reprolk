<?php

class Template extends BaseModel {
	
	public $id;
	public $gid;
	public $name;
	
	public static $fields = array('gid', 'name');
	
	public static function tableName() {
		return 'templates';
	}
	
	/* Actions */
	
	public function remove() {
		return !is_null($this->id) && self::delete([self::field('id') . ' = ' . $this->id]);
	}
	
	/* Can */
	
	public function canEdit() {
		return Account::isAdmin() || Account::getRawGid()==$this->gid;
	}
	
	/* Static methods */
	
	public static function getAll($gid) {
		$fields = array();
		$fields[] = self::field('*');
		
		$where = array();
		$where[] = self::field('gid') . ' = ' . $gid;
		return self::selectRows($fields, $where);
	}
	
	public static function byId($id) {
		return self::selectRow(null, [self::field('id') . ' = ' . $id]);
	}
	
	public static function add($gid, $name) {
		return self::insertRow(self::$fields, array($gid, $name));
	}
	
}

?>