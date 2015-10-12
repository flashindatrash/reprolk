<?php

include_once '../core/objects/SQLOrderBy.php';

class DataBaseManager {

	private $config;
	private $connection;
	private $history = array();
   
	public function __construct($config) {
		$this->config = $config;
	}
	
	public function connect() {
		$this->connection = mysql_connect($this->config['host'], $this->config['username'], $this->config['password']);
		if ($this->connection) {
			mysql_query("set names utf8");
			mysql_select_db($this->config['name'], $this->connection);
			return true;
		}
		return false;
	}
	
	public function query($str) {
		$this->history[] = $str;
		return mysql_query($str, $this->connection);
	}
	
	public function select($from, $select = NULL, $where = NULL, $join = NULL, $order = NULL, $range = NULL) {
		$select = $this->convertSelect($select);
		$from = ' from ' . $this->tableName($from);
		$join = is_null($join) ? '' : ' ' . join(' ', $join);
		$order = is_null($order) ? '' : ' ' . $order->toString();
		$range = ' limit ' . (is_null($range) ? '0, 1' : $range);
		
		$query = $this->query($select . $from . $join . self::convertWhere($where) . $order . $range);
		return $query ? $query : null;
	}
	
	public function selectRow($from, $className = NULL, $select = NULL, $where = NULL, $join = NULL) {
		$result = $this->select($from, $select, $where, $join);
		return !is_null($result) && mysql_num_rows($result)>0 ? mysql_fetch_object($result, $className) : null;
	}
	
	public function selectRows($from, $className = NULL, $select = NULL, $where = NULL, $join = NULL, $order = NULL, $range = NULL) {
		if (is_null($range)) $range = '0, 300';
		$result = $this->select($from, $select, $where, $join, $order, $range);
		$rows = array();
		while ($row = mysql_fetch_object($result, $className)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function insert($into, $fields, $values) {
		$str = 'insert into ' . $this->tableName($into) . ' (' . self::array2fields($fields) . ') values ' . $values .';';
		if ($this->query($str)) {
			return mysql_insert_id();
		}
		return null;
	}
	
	public function insertRow($into, $fields, $values) {
		if (count($fields)==0 || count($values)==0 || count($fields)!=count($values)) return null;
		return self::insert($into, $fields, self::array2insert([self::array2values($values)]));
	}
	
	public function insertRows($into, $fields, $values) {
		if (count($fields)==0 || count($values)==0) return null;
		$array = array();
		foreach ($values as $value) {
			if (count($fields)!=count($value)) continue;
			$array[] = self::array2values($value);
		}
		if (count($array)==0) return null;
		return self::insert($into, $fields, self::array2insert($array));
	}
	
	public function delete($from, $where = NULL) {
		if ($this->query('delete from ' . $this->tableName($from) . self::convertWhere($where))) {
			return true;
		}
		return false;
	}
	
	public function update($from, $fields, $values, $where = NULL) {
		if (count($fields) == 0 || count($fields) != count($values)) return false;
		
		$sets = array();
		foreach ($fields as $i => $field) {
			$sets[] = $field . ' = "' . $values[$i] . '"';
		}
		
		$str = 'update ' . $this->tableName($from)  . ' set ' . join(', ', $sets) . self::convertWhere($where);
		if ($this->query($str)) {
			return true;
		}
		return false;
	}
	
	public static function array2fields($arr) {
		foreach ($arr as $i => $v) {
			$arr[$i] = strpos($v, '.')===false ? '`' . $v .  '`' : $v;
		}
		return join(', ', $arr);
	}
	
	public static function array2values($arr) {
		return '"' . join('", "', $arr) . '"';
	}
	
	public static function array2insert($arr) {
		return '(' . join('), (', $arr) . ')';
	}
	
	public function tableName($name) {
		return $this->config['prefix'] . $name;
	}
	
	public function getHistory() {
		return $this->history;
	}
	
	private function convertSelect($select) {
		return 'select ' . (is_null($select) || count ($select)==0 ? '*' : self::array2fields($select));
	}
	
	private function convertWhere($where) {
		return ' where ' . (is_null($where) || count($where)==0 ? '1' : join(' and ', $where));
	}
	
}