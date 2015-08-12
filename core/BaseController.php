<?php

class BaseController {

	protected static $dataBaseManager;

	public function initDataBase() {
		if (!self::$db) {
			global $config;
			self::$db = new DataBaseManager($config);
		}
	}

}