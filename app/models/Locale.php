<?php

class Locale extends BaseModel {
	
	const DEFAULT_LANGUAGE = 'en';
	const KEY = 'str';
	
	public $str;
	public $isNew; //true, если ее нет в БД
	
	public static function tableName() {
		return 'locales';
	}
	
	public function edit($values) {
		$fields = self::getLanguages();
		$success = false;
		
		if ($this->isNew) {
			$fields[] = self::KEY;
			$values[] = $this->str;
			$success = self::insertRow($fields, $values) !== null;
		} else {
			$success = self::editByKey($this->str, $fields, $values);
		}
		
		if ($success) {
			foreach ($fields as $i => $field) {
				$this->$field = $values[$i];
			}
		}
		
		return $success;
	}
	
	public function value($lang = null) {
		if (is_null($lang)) {
			$lang = Account::getLang();
		}
		return $this->$lang;
	}
	
	private static $languages;
	public static function getLanguages() {
		if (!is_null(self::$languages)) return self::$languages;
		self::$languages = reArray(self::columns(), null, 'Field');
		removeArrayItem(self::KEY, self::$languages);
		return self::$languages;
	}
	
	public static function byKey($str) {
		$where = array();
		$where[] = self::field(self::KEY) . ' = "' . $str . '"';
		$locale = self::selectRow(null, $where);
		if (is_null($locale)) {
			$locale = new Locale();
			$locale->str = $str;
			$locale->isNew = true;
			foreach (self::getLanguages() as $lang) {
				$locale->$lang = '';
			}
		}
		return $locale;
	}
	
	public static function getAll($lang, $range = null) {
		$fields = array();
		$fields[] = self::field(self::KEY);
		$fields[] = self::field($lang);
		return self::selectRows($fields, null, null, null, $range);
	}
	
	public static function editByKey($key, $fields, $values) {
		$where = array();
		$where[] = self::field(self::KEY) . ' = "' . $key . '"';
		return self::update($fields, $values, $where);
	}
	
	public static function getCountTotal($lang = null) {
		return self::getCount(!is_null($lang) ? [self::field($lang) . ' != ""'] : null);
	}
	
}

?>