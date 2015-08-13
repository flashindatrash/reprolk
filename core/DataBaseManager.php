<?php

class DataBaseManager {

	private $connection;
	private $prefix;
   
	public function __construct($config) {
		$this->prefix = $config['prefix'];
		$this->connection = mysql_connect($config['host'], $config['username'], $config['password']);
		if ($this->connection) {
			mysql_select_db($config['name'], $this->connection);
		}
	}
	
	//--------------------------------------------------------------------------
	//
	//  Public methods
	//
	//--------------------------------------------------------------------------
	
	public function selectRow($from, $select = "*", $where = "1", $className = NULL) {
		$query = mysql_query("select ".$select." from ".$this->tableName($from)." where ".$where, $this->connection);
		if ($query) {
			return mysql_fetch_object($query, $className);
		}
		return NULL;
	}
	
	//--------------------------------------------------------------------------
	//
	//  Private methods
	//
	//--------------------------------------------------------------------------
	
	private function tableName($name) {
		return $this->prefix.$name;
	}
	
}