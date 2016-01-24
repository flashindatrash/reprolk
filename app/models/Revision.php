<?php

class Revision extends BaseModel {
	
	public $oid;
	public $rid;
	public $date;
	public $file;
	
	public static function tableName() {
		return 'revisions';
	}
	
	public static function getAll($oid, $order_by = null) {
		$where = array();
		$where[] = self::field('oid') . ' = ' . $oid;
		return self::selectRows(null, $where, null, $order_by);
	}
}

?>