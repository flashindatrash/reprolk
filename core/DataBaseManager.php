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
		$range = is_null($range) ? '' : ' limit ' . $range;
		
		$query = $this->query($select . $from . $join . self::convertWhere($where) . $order . $range);
		return $query ? $query : null;
	}
	
	public function getCount($from, $where = NULL, $join = null) {
		$select = 'select COUNT(*) as count';
		$from = ' from ' . $this->tableName($from);
		$join = is_null($join) ? '' : ' ' . join(' ', $join);
		$query = $this->query($select . $from . $join . self::convertWhere($where));
		$result = !is_null($query) && mysql_num_rows($query)>0 ? mysql_fetch_object($query) : null;
		return !is_null($result) ? $result->count : 0;
	}
	
	public function selectRow($from, $className = NULL, $select = NULL, $where = NULL, $join = NULL) {
		$result = $this->select($from, $select, $where, $join, null, '0, 1');
		return !is_null($result) && mysql_num_rows($result)>0 ? mysql_fetch_object($result, $className) : null;
	}
	
	public function selectRows($from, $className = NULL, $select = NULL, $where = NULL, $join = NULL, $order = NULL, $range = NULL) {
		$result = $this->select($from, $select, $where, $join, $order, $range);
		$rows = array();
		if (!is_null($result)) {
			while ($row = mysql_fetch_object($result, $className)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}
	
	public function insert($into, $fields, $values, $duplicate = NULL) {
		$str = 'insert into ' . $this->tableName($into) . ' (' . self::array2fields($fields) . ') values ' . $values . (is_null($duplicate) ? '' : ' ' . $duplicate->toString()) . ';';
		if ($this->query($str)) {
			return mysql_insert_id();
		}
		return null;
	}
	
	public function insertRow($into, $fields, $values, $duplicate = NULL) {
		if (count($fields)==0 || count($values)==0 || count($fields)!=count($values)) return null;
		return self::insert($into, $fields, self::array2insert([self::array2values($values)]), $duplicate);
	}
	
	public function insertRows($into, $fields, $values, $duplicate = NULL) {
		if (count($fields)==0 || count($values)==0) return null;
		$array = array();
		foreach ($values as $value) {
			if (count($fields)!=count($value)) continue;
			$array[] = self::array2values($value);
		}
		if (count($array)==0) return null;
		return self::insert($into, $fields, self::array2insert($array), $duplicate);
	}
	
	public function delete($from, $where = NULL, $join = NULL) {
		$join = is_null($join) ? '' : ' using ' . $from . ' ' . join(' ', $join); //без using не работает
		$str = 'delete from ' . $this->tableName($from) . $join . self::convertWhere($where);
		return $this->query($str) ? true : false;
	}
	
	public function update($from, $fields, $values, $where = NULL) {
		if (count($fields) == 0 || count($fields) != count($values)) return false;
		
		$sets = array();
		foreach ($fields as $i => $field) {
			$sets[] = $field . ' = "' . $values[$i] . '"';
		}
		
		$str = 'update ' . $this->tableName($from)  . ' set ' . join(', ', $sets) . self::convertWhere($where);
		return $this->query($str) ? true : false;
	}
	
	public function column($from, $field) {
		$str = 'show columns from ' . $this->tableName($from)  . ' like "' . $field . '"';
		$result = $this->query($str);
		return !is_null($result) && mysql_num_rows($result)>0 ? mysql_fetch_object($result, 'Column') : null;
	}
	
	public function columns($from) {
		$str = 'show columns from ' . $this->tableName($from);
		$result = $this->query($str);
		
		if (is_null($result)) return array();
		
		$rows = array();
		while ($row = mysql_fetch_object($result, 'Column')) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	public function addColumn($table, $column, $type, $mandatory, $default) {
		$str = 'alter table ' . $this->tableName($table)  . ' add `' . $column . '` ' . $type . ' ' . ($mandatory ? 'not ' : '') . 'null' . (is_null($default) ? '' : ' default ' . $default) . ';';
		return $this->query($str) ? true : false;
	}
	
	public function dropColumn($table, $column) {
		$str = 'alter table ' . $this->tableName($table)  . ' drop `'. $column . '`;';
		return $this->query($str) ? true : false;
	}
	
	public function truncate($table) {
		$str = 'truncate ' . $this->tableName($table)  . ';';
		return $this->query($str) ? true : false;
	}
	
	public static function array2fields($arr) {
		foreach ($arr as $i => $v) {
			$arr[$i] = strpos($v, '.')===false ? '`' . $v .  '`' : $v;
		}
		return join(', ', $arr);
	}
	
	public static function array2values($arr) {
		$return = array();
		foreach ($arr as $i => $value) {
			if (is_null($value)) {
				$return[] = 'NULL';
			} else {
				$return[] = '"' . $value . '"';
			}
		}
		return join(', ', $return);
	}
	
	public static function array2insert($arr) {
		return '(' . join('), (', $arr) . ')';
	}
	
	public static function convertWhere($where, $comparison = 'and') {
		return ' where ' . (is_null($where) || count($where)==0 ? '1' : self::where($where, $comparison));
	}
	
	public static function where($where, $comparison = 'and') {
		return join(' '. $comparison. ' ', $where);
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
	
}