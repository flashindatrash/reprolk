<?php

include_once '../core/Config.php';
include_once '../core/Application.php';
include_once '../core/DataBaseManager.php';
include_once '../core/FTPManager.php';
include_once '../core/BaseController.php';
include_once '../core/BaseModel.php';
include_once '../core/View.php';
include_once '../core/objects/Hook.php';
include_once '../core/objects/Routes.php';
include_once '../core/objects/Route.php';
include_once '../core/objects/AccountRoute.php';
include_once '../core/objects/ApiRoute.php';
include_once '../core/objects/Session.php';
include_once '../core/objects/MenuItem.php';
include_once '../core/objects/Redirect.php';
include_once '../core/objects/UserAccess.php';
include_once '../core/objects/Account.php';
include_once '../core/interfaces/IAuthentication.php';

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
	return isset($_POST[$val]) ? validQuotes($_POST[$val]) : '';
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
	return $b=="1" || $b=="true";
}

function checkbox2bool($value) {
	return $value=='on' ? 1 : 0;
}

function int($s) {
	return(int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);
}

function validQuotes($text) {
	return str_replace('"', '\\"', $text);
}

function stripQuotes($text) {
	return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
}

function validSymbols($text) {
	return preg_replace("/[^a-zA-Z0-9_]/","",$text);
}

function removeArrayItem($value, &$arr) {
	if(($key = array_search($value, $arr)) !== false) {
		unset($arr[$key]);
	}
	
	$arr = array_values($arr);
}

function reArray($array, $key, $value) {
	$a = array();
	foreach ($array as $item) {
		if (is_null($key)) $a[] = is_null($value) ? $item : $item->$value;
		else $a[$item->$key] = is_null($value) ? $item : $item->$value;
	}
	return $a;
}
	
function reArrayFiles($file_post) {
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

function translit($string) {
	$replace=array(
		"'"=>"",
		"`"=>"",
		"а"=>"a","А"=>"a",
		"б"=>"b","Б"=>"b",
		"в"=>"v","В"=>"v",
		"г"=>"g","Г"=>"g",
		"д"=>"d","Д"=>"d",
		"е"=>"e","Е"=>"e",
		"ж"=>"zh","Ж"=>"zh",
		"з"=>"z","З"=>"z",
		"и"=>"i","И"=>"i",
		"й"=>"y","Й"=>"y",
		"к"=>"k","К"=>"k",
		"л"=>"l","Л"=>"l",
		"м"=>"m","М"=>"m",
		"н"=>"n","Н"=>"n",
		"о"=>"o","О"=>"o",
		"п"=>"p","П"=>"p",
		"р"=>"r","Р"=>"r",
		"с"=>"s","С"=>"s",
		"т"=>"t","Т"=>"t",
		"у"=>"u","У"=>"u",
		"ф"=>"f","Ф"=>"f",
		"х"=>"h","Х"=>"h",
		"ц"=>"c","Ц"=>"c",
		"ч"=>"ch","Ч"=>"ch",
		"ш"=>"sh","Ш"=>"sh",
		"щ"=>"sch","Щ"=>"sch",
		"ъ"=>"","Ъ"=>"",
		"ы"=>"y","Ы"=>"y",
		"ь"=>"","Ь"=>"",
		"э"=>"e","Э"=>"e",
		"ю"=>"yu","Ю"=>"yu",
		"я"=>"ya","Я"=>"ya",
		"і"=>"i","І"=>"i",
		"ї"=>"yi","Ї"=>"yi",
		"є"=>"e","Є"=>"e"
	);
	return $str=iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
}

function toUTF($text) {
	return iconv('Windows-1251', "UTF-8//IGNORE", $text);
}

function require_class($fileName, $className) {
	if (file_exists($fileName)) require_once ($fileName);
	if (class_exists($className)) return new $className;
	return null;
}

function server_getenv($var_name) {
    if (isset($_SERVER[$var_name])) {
        return $_SERVER[$var_name];
    }

    if (isset($_ENV[$var_name])) {
        return $_ENV[$var_name];
    }

    if (getenv($var_name)) {
        return getenv($var_name);
    }

    if (function_exists('apache_getenv')
        && apache_getenv($var_name, true)
    ) {
        return apache_getenv($var_name, true);
    }

    return '';
}

function download_header($filename, $mimetype, $length = 0) {
	/* Replace all possibly dangerous chars in filename */
	$filename = str_replace(array(';', '"', "\n", "\r"), '-', $filename);
	if (!empty($filename)) {
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
	}
	header('Content-Type: ' . $mimetype);
	// inform the server that compression has been done,
	// to avoid a double compression (for example with Apache + mod_deflate)
	$notChromeOrLessThan43 = USR_BROWSER_AGENT != 'CHROME' // see bug #4942
		|| (USR_BROWSER_AGENT == 'CHROME' && USR_BROWSER_VER < 43);
	if (strpos($mimetype, 'gzip') !== false && $notChromeOrLessThan43) {
		header('Content-Encoding: gzip');
	}
	header('Content-Transfer-Encoding: binary');
	if ($length > 0) {
		header('Content-Length: ' . $length);
	}
}

function __autoload($class_name) {
	$file = '../app/models/' . $class_name . '.php';
    if (file_exists($file)) include_once $file;
}