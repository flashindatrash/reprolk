<?php

class UserPlugin extends BaseModel {
	
	public $plugin;
	
	public $name; //plugin -> Plugin::name
	public $files; //plugin -> Plugin::files
	
	public static function tableName() {
		return 'user_plugins';
	}
	
	/* Methods */
	
	private $uFile;
	public function getFiles() {
		if (is_null($this->uFile)) {
			$uFile = unserialize($this->files);
		}
		return $uFile;
	}
	
	public function connect($file) {
		$files = $this->getFiles();
		
		if (!in_array($file, $files)) return;
		
		foreach ($files as $f) {
			if ($file!=$f) continue;
			
			$f = Application::$config['plugin']['dir'] . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $f;
			if (file_exists($f)) {
				require_once($f);
			}
		}
	}
	
	/* Static methods */
	
	public static function add($plugin, $uid = null, $gid = null) {
		$fields = array();
		$values = array();
		
		$fields[] = 'plugin';
		$values[] = $plugin;
		
		$fields[] = 'uid';
		$values[] = $uid;
		
		$fields[] = 'gid';
		$values[] = $gid;
		
		return self::insertRow($fields, $values);
	}
	
	public static function deleteByName($plugin, $uid = null, $gid = null) {
		$where = array();
		$where[] = self::field('plugin') . ' = "' . $plugin . '"';
		if (!is_null($uid)) $where[] = self::field('uid') . ' = ' . $uid;
		if (!is_null($gid)) $where[] = self::field('gid') . ' = ' . $gid;
		
		return self::delete($where);
	}
	
	public static function getAll($uid = null, $gid = null) {
		$fields = array();
		$fields[] = self::field('name', Plugin::tableName(), 'name');
		$fields[] = self::field('files', Plugin::tableName(), 'files');
		
		$join = array();
		$join[] = self::inner('name', Plugin::tableName(), 'plugin');
		
		$where = array();
		if (!is_null($uid)) $where[] = self::field('uid') . ' = ' . $uid;
		if (!is_null($gid)) $where[] = self::field('gid') . ' = ' . $gid;
		
		if (count($where)>=2) {
			$where = [DataBaseManager::where($where, 'or')];
		}
		
		return self::selectRows($fields, $where, $join, new SQLOrderBy('name', 'desc', 'name'));
	}
}

?>