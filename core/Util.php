<?php

class Util {
	
	public static function inc($type, $file) {
		$config = Application::$config;
		if (is_null($config) || is_null($type) || !isset($config['app'][$type])) return;
		$path = $config['app'][$type] . $file;
		if (file_exists($path)) {
			include_once($path);
		}
	}
	
	public static function paging(&$currentPage, &$totalPages, $total, $perPage, $from = 'get') {
		$totalPages = ceil($total / $perPage);
		if ($from=='get') {
			$currentPage = hasGet('page') ? int(get('page')) : 0;
		} else {
			$currentPage = hasPost('page') ? int(post('page')) : 0;
		}
		
		if ($totalPages==0) $currentPage = 0;
		else if ($currentPage>$totalPages-1) $currentPage = $totalPages-1;
		else if ($currentPage<0) $currentPage = 0;
	}
	
}

?>