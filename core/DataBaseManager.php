<?php

class DataBaseManager {

	private $connection;
	private $prefix;
	private $history = array();
   
	public function __construct($config) {
		$this->prefix = $config['prefix'];
		$this->connection = mysql_connect($config['host'], $config['username'], $config['password']);
		if ($this->connection) {
			mysql_query("set names utf8");
			mysql_select_db($config['name'], $this->connection);
		}
	}
	
	public function query($str) {
		$this->history[] = $str;
		return mysql_query($str, $this->connection);
	}
	
	public function select($from, $select = '*', $where = '1', $className = NULL, $range = '0, 1', $join = NULL) {
		$str = 'select ' . $select . ' from ' . $this->tableName($from) . (is_null($join) ? '' : ' inner join ' . $join) . ' where ' . $where . ' limit ' . $range;
		$query = $this->query($str);
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
		$str = 'insert into `' . $this->tableName($from) . '` (' . self::array2fields($fields) . ') values ' . $values .';';
		if ($this->query($str)) {
			return mysql_insert_id();
		}
		return null;
	}
	
	public function insertRow($from, $fields, $values) {
		if (count($fields)==0 || count($values)==0 || count($fields)!=count($values)) return null;
		return self::insert($from, $fields, self::array2insert([self::array2values($values)]));
	}
	
	public function insertRows($from, $fields, $values) {
		if (count($fields)==0 || count($values)==0) return null;
		$array = array();
		foreach ($values as $value) {
			if (count($fields)!=count($value)) continue;
			$array[] = self::array2values($value);
		}
		if (count($array)==0) return null;
		return self::insert($from, $fields, self::array2insert($array));
	}
	
	public function delete($from, $where = '1') {
		$str = 'delete from `' . $this->tableName($from) . '` where ' . $where;
		if ($this->query($str)) {
			return true;
		}
		return false;
	}
	
	public static function array2fields($arr) {
		return '`' . join('`, `', $arr) . '`';
	}
	
	public static function array2values($arr) {
		return '"' . join('", "', $arr) . '"';
	}
	
	public static function array2insert($arr) {
		return '(' . join('), (', $arr) . ')';
	}
	
	public function tableName($name) {
		return $this->prefix.$name;
	}
	
	public function getHistory() {
		return $this->history;
	}
	
}