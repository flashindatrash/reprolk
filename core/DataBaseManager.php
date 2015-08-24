<?php

class DataBaseManager {

	private $connection;
	private $prefix;
   
	public function __construct($config) {
		$this->prefix = $config['prefix'];
		$this->connection = mysql_connect($config['host'], $config['username'], $config['password']);
		if ($this->connection) {
			mysql_query("set names utf8");
			mysql_select_db($config['name'], $this->connection);
		}
	}
	
	public function selectRow($from, $select = '*', $where = '1', $className = NULL) {
		$str = 'select ' . $select . ' from ' . $this->tableName($from) . ' where ' . $where;
		$query = mysql_query($str, $this->connection);
		if ($query) {
			$return = mysql_fetch_object($query, $className);
			if ($return===false) return null;
			return $return;
		}
		return null;
	}
	
	public function insertRow($from, $fields, $values) {
		if (count($fields)==0 || count($values)==0 || count($fields)!=count($values)) return null;
		$f = '`' . join('`, `', $fields) . '`';
		$v = '"' . join('", "', $values) . '"';
		
		$str = 'INSERT INTO `' . $this->tableName($from) . '` (' . $f . ') VALUES (' . $v . ');';
		$query = mysql_query($str, $this->connection);
		if ($query) {
			return mysql_insert_id();
		}
		return null;
	}
	
	private function tableName($name) {
		return $this->prefix.$name;
	}
	
}