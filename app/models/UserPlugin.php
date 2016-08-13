<?php

class UserPlugin extends BaseModel {
	
	public $plugin;
	
	public $name; //plugin -> Plugin::name
	public $route; //plugin -> Plugin::route
	public $files; //plugin -> Plugin::files
	
	const FIELD_PLUGIN = 'plugin';
	const FIELD_UID = 'uid';
	const FIELD_GID = 'gid';
	
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
	
	public function connect() {
		if (!is_null($this->route) && $this->route!=Application::$routes->current->name) {
			return; //этот плангин не подходит по роуту
		}
		
		$files = $this->getFiles();
		
		foreach ($files as $f) {
			$file = new File();
			$file->name = Application::$config['plugin']['dir'] . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . $f;
			if ($file->extension()==File::EXT_PHP && file_exists($file->name)) {
				require_once($file->name);
			}
		}
	}
	
	/* Static methods */
	
	public static function add($plugin, $uid = null, $gid = null) {
		$fields = array();
		$values = array();
		
		$fields[] = UserPlugin::FIELD_PLUGIN;
		$values[] = $plugin;
		
		$fields[] = UserPlugin::FIELD_UID;
		$values[] = $uid;
		
		$fields[] = UserPlugin::FIELD_GID;
		$values[] = $gid;
		
		return self::insertRow($fields, $values);
	}
	
	public static function deleteByName($plugin, $uid = null, $gid = null) {
		$where = array();
		$where[] = self::field(UserPlugin::FIELD_PLUGIN) . ' = "' . $plugin . '"';
		if (!is_null($uid)) $where[] = self::field(UserPlugin::FIELD_UID) . ' = ' . $uid;
		if (!is_null($gid)) $where[] = self::field(UserPlugin::FIELD_GID) . ' = ' . $gid;
		
		return self::delete($where);
	}
	
	public static function getAll($uid = null, $gid = null) {
		$fields = array();
		foreach (Plugin::$fields as $field) {
			$fields[] = self::field($field, Plugin::tableName(), $field);
		}
		
		$join = array();
		$join[] = self::inner(Plugin::FIELD_NAME, Plugin::tableName(), UserPlugin::FIELD_PLUGIN);
		
		$where = array();
		if (!is_null($uid)) $where[] = self::field(UserPlugin::FIELD_UID) . ' = ' . $uid;
		if (!is_null($gid)) $where[] = self::field(UserPlugin::FIELD_GID) . ' = ' . $gid;
		
		if (count($where)>=2) {
			$where = [DataBaseManager::where($where, 'or')];
		}
		
		return self::selectRows($fields, $where, $join, new SQLOrderBy('name', 'desc', 'name'));
	}
}

?>