<?php

include '../core/Application.php';
include '../core/DataBaseManager.php';
include '../core/BaseController.php';
include '../core/BaseModel.php';
include '../core/View.php';
include '../core/objects/Route.php';
include '../core/objects/Routes.php';
include '../core/objects/Session.php';
include '../core/objects/MenuItem.php';
include '../core/objects/Redirect.php';
include '../core/objects/UserAccess.php';
include '../core/objects/Account.php';

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

function checkbox2bool($value) {
	return $value=='on' ? 1 : 0;
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