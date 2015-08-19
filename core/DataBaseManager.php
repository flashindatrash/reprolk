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
		$query = mysql_query('select ' . $select . ' from ' . $this->tableName($from) . ' where ' . $where, $this->connection);
		if ($query) {
			$return = mysql_fetch_object($query, $className);
			if ($return===false) return null;
			return $return;
		}
		return null;
	}
	
	private function tableName($name) {
		return $this->prefix.$name;
	}
	
}