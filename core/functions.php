<?php

include_once '../core/Application.php';
include_once '../core/DataBaseManager.php';
include_once '../core/FTPManager.php';
include_once '../core/BaseController.php';
include_once '../core/BaseModel.php';
include_once '../core/View.php';
include_once '../core/objects/Route.php';
include_once '../core/objects/Routes.php';
include_once '../core/objects/Session.php';
include_once '../core/objects/MenuItem.php';
include_once '../core/objects/Redirect.php';
include_once '../core/objects/UserAccess.php';
include_once '../core/objects/Account.php';
		
function p($x=''){
   print ps($x);
}

function ps($x=''){
   if(is_object($x) || is_array($x)){
      $x = '<pre>'. pr($x) . '</pre>';
   }
   return  "<div>{$x}</div>\n";
}

function pr($x=null){
   return print_r($x, true);
}

function post($val) {
	return isset($_POST[$val]) ? $_POST[$val] : '';
}

function get($val) {
	return isset($_GET[$val]) ? $_GET[$val] : '';
}

function gets() {
	$a = array();
	foreach ($_GET as $key => $value) {
		if ($key!='_url')
			$a[$key] = $value;
	}
	return $a;
}

function hasPost($val) {
	return post($val)!='';
}

function hasGet($val) {
	return get($val)!='';
}

function session($val) {
	return isset($_SESSION[$val]) ? $_SESSION[$val] : '';
}

function hasSession($val) {
	return session($val)!='';
}

function toBool($b) {
	return $b=="1";
}

function checkbox2bool($value) {
	return $value=='on' ? 1 : 0;
}

function reArrayFiles(&$file_post) {
	$file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

function objectToArray($object) {
	if(!is_object($object) && !is_array($object)) {
		return $object;
	}
	if (is_object($object)) {
		$object = get_object_vars($object);
	}
	return array_map('objectToArray',$object);
}

function require_class($fileName, $className) {
	if (file_exists($fileName)) require_once ($fileName);
	if (class_exists($className)) return new $className;
	return null;
}

function __autoload($class_name) {
	$file = '../app/models/' . $class_name . '.php';
    if (file_exists($file)) include $file;
}