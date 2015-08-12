<?php

class BaseController {

	protected static $dataBaseManager;

	public function __construct() {
		if (!self::$db) {
			global $config;
			self::$db = new DataBaseManager($config);
		}
	}

}