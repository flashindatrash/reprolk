<?php

include '../core/Application.php';
include '../core/DataBaseManager.php';
include '../core/BaseController.php';
include '../core/BaseModel.php';

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

function require_class($fileName, $className) {
	if (file_exists($fileName)) require_once ($fileName);
	if (class_exists($className)) return new $className;
	return null;
}

function __autoload($class_name) {
	$file = '../app/models/' . $class_name . '.php';
    if (file_exists($file)) include $file;
}