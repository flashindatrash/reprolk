<?php

class DataBaseManager {

	private $connection;
   
	public function __construct($config) {
		$this->connection = mysql_connect($config['database']['host'], $config['database']['username'], $config['database']['password']);
		if ($this->connection) {
			mysql_select_db($config['database']['name'], $this->connection)
		}
	}
	
}