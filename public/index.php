<?php

try {

	define('PATH_PUBLIC_DIR', __DIR__);

	require_once ('../core/functions.php');

	$config = parse_ini_file("../app/config/config.ini", true);
	
	$view = new ViewController($_GET['_url']);

} catch (Exception $e) {

	echo $e->getMessage();
	
}