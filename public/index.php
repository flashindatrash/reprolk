<?php

try {

	define('PATH_PUBLIC_DIR', __DIR__);

	require_once ('../core/functions.php');

	//$config = new Ini("../app/config/config.ini");
	
	print 's';

} catch (Exception $e) {

	echo $e->getMessage();
	
}