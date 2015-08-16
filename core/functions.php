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