<?php

class BaseController {

	protected static $dataBaseManager;

	public function __construct() {
		if (!self::$db) {
			self::$db = new DataBaseManager($config);
		}
	}

}