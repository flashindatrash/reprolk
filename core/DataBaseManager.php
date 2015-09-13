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
	
	public function select($from, $select = '*', $where = '1', $className = NULL, $range = '0, 1', $join = NULL) {
		$str = 'select ' . $select . ' from ' . $this->tableName($from) . (is_null($join) ? '' : ' inner join ' . $join) . ' where ' . $where . ' limit ' . $range;
		$query = mysql_query($str, $this->connection);
		return $query ? $query : null;
	}
	
	public function selectRow($from, $select = '*', $where = '1', $className = NULL, $join = NULL) {
		$result = $this->select($from, $select, $where, $className, '0, 1', $join);
		return !is_null($result) ? mysql_fetch_object($result, $className) : null;
	}
	
	public function selectRows($from, $select = '*', $where = '1', $className = NULL, $range = '0, 10', $join = NULL) {
		$result = $this->select($from, $select, $where, $className, $range, $join);
		$rows = array();
		while ($row = mysql_fetch_object($result, $className)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function insert($from, $fields, $values) {
		if (count($fields)==0 || count($values)==0 || count($fields)!=count($values)) return null;
		$str = 'INSERT INTO `' . $this->tableName($from) . '` (' . self::array2fields($fields) . ') VALUES (' . self::array2values($values) . ');';
		$query = mysql_query($str, $this->connection);
		if ($query) {
			return mysql_insert_id();
		}
		return null;
	}
	
	public static function array2fields($arr) {
		return '`' . join('`, `', $arr) . '`';
	}
	
	public static function array2values($arr) {
		return '"' . join('", "', $arr) . '"';
	}
	
	public function tableName($name) {
		return $this->prefix.$name;
	}
	
}