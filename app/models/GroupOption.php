<?php

class GroupOption extends BaseModel {
	
	public $gid;
	public $oid;
	public $name; //option->name
	
	const FIELD_GID = 'gid';
	const FIELD_OID = 'oid';
	
	public static function tableName() {
		return 'group_options';
	}
	
	public static function getAll($fid, $gid = null) {
		if (is_null($gid)) {
			$gid = Account::getGid();
		}
		
		$fields = array();
		$fields[] = self::field('*');
		$fields[] = self::field(Option::FIELD_NAME, Option::tableName(), Option::FIELD_NAME);
		$fields[] = self::field(Option::FIELD_FID, Option::tableName(), Option::FIELD_FID);
		
		return self::selectRows(self::createFields(), self::createWhere($fid, $gid), self::createJoin());
	}
	
	public static function set($fid, $gid, $options) {
		$values = array();
		foreach ($options as $option) {
			$values[] = array($gid, $option);
		}
		
		$delete = self::delete(self::createWhere($fid, $gid), self::createJoin());
		$insert = self::insertRows([GroupOption::FIELD_GID, GroupOption::FIELD_OID], $values);
		
		return $delete && !is_null($insert);
	}

	private static function createFields() {
		$fields = array();
		$fields[] = self::field(GroupOption::FIELD_GID);
		$fields[] = self::field(GroupOption::FIELD_OID);
		$fields[] = self::field(Option::FIELD_NAME, Option::tableName(), Option::FIELD_NAME);
		$fields[] = self::field(Option::FIELD_FID, Option::tableName(), Option::FIELD_FID);
		return $fields;
	}
	
	private static function createWhere($fid, $gid) {
		$where = array();
		$where[] = self::field(GroupOption::FIELD_GID) . ' = ' . $gid;
		$where[] = self::field(Option::FIELD_FID, Option::tableName()) . ' = ' . $fid;
		return $where;
	}

	private static function createJoin() {
		$join = array();
		$join[] = self::inner(Option::FIELD_ID, Option::tableName(), GroupOption::FIELD_OID);
		return $join;
	}
}

?>