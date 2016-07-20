<?php

class Config {

	public static function check_client() {
        if (server_getenv('HTTP_USER_AGENT')) {
            $HTTP_USER_AGENT = server_getenv('HTTP_USER_AGENT');
        } else {
            $HTTP_USER_AGENT = '';
        }

        $is_mozilla = preg_match(
            '@Mozilla/([0-9].[0-9]{1,2})@',
            $HTTP_USER_AGENT,
            $mozilla_version
        );

        if (preg_match(
            '@Opera(/| )([0-9].[0-9]{1,2})@',
            $HTTP_USER_AGENT,
            $log_version
        )) {
            self::set('USR_BROWSER_VER', $log_version[2]);
            self::set('USR_BROWSER_AGENT', 'OPERA');
        } elseif (preg_match(
            '@(MS)?IE ([0-9]{1,2}.[0-9]{1,2})@',
            $HTTP_USER_AGENT,
            $log_version
        )) {
            self::set('USR_BROWSER_VER', $log_version[2]);
            self::set('USR_BROWSER_AGENT', 'IE');
        } elseif (preg_match(
            '@Trident/(7)\.0@',
            $HTTP_USER_AGENT,
            $log_version
        )) {
            self::set('USR_BROWSER_VER', intval($log_version[1]) + 4);
            self::set('USR_BROWSER_AGENT', 'IE');
        } elseif (preg_match(
            '@OmniWeb/([0-9].[0-9]{1,2})@',
            $HTTP_USER_AGENT,
            $log_version
        )) {
            self::set('USR_BROWSER_VER', $log_version[1]);
            self::set('USR_BROWSER_AGENT', 'OMNIWEB');
            // Konqueror 2.2.2 says Konqueror/2.2.2
            // Konqueror 3.0.3 says Konqueror/3
        } elseif (preg_match(
            '@(Konqueror/)(.*)(;)@',
            $HTTP_USER_AGENT,
            $log_version
        )) {
            self::set('USR_BROWSER_VER', $log_version[2]);
            self::set('USR_BROWSER_AGENT', 'KONQUEROR');
            // must check Chrome before Safari
        } elseif ($is_mozilla
            && preg_match('@Chrome/([0-9.]*)@', $HTTP_USER_AGENT, $log_version)
        ) {
            self::set('USR_BROWSER_VER', $log_version[1]);
            self::set('USR_BROWSER_AGENT', 'CHROME');
            // newer Safari
        } elseif ($is_mozilla
            && preg_match('@Version/(.*) Safari@', $HTTP_USER_AGENT, $log_version)
        ) {
            self::set(
                'USR_BROWSER_VER', $log_version[1]
            );
            self::set('USR_BROWSER_AGENT', 'SAFARI');
            // older Safari
        } elseif ($is_mozilla
            && preg_match('@Safari/([0-9]*)@', $HTTP_USER_AGENT, $log_version)
        ) {
            self::set(
                'USR_BROWSER_VER', $mozilla_version[1] . '.' . $log_version[1]
            );
            self::set('USR_BROWSER_AGENT', 'SAFARI');
            // Firefox
        } elseif (! mb_strstr($HTTP_USER_AGENT, 'compatible')
            && preg_match('@Firefox/([\w.]+)@', $HTTP_USER_AGENT, $log_version)
        ) {
            self::set(
                'USR_BROWSER_VER', $log_version[1]
            );
            self::set('USR_BROWSER_AGENT', 'FIREFOX');
        } elseif (preg_match('@rv:1.9(.*)Gecko@', $HTTP_USER_AGENT)) {
            self::set('USR_BROWSER_VER', '1.9');
            self::set('USR_BROWSER_AGENT', 'GECKO');
        } elseif ($is_mozilla) {
            self::set('USR_BROWSER_VER', $mozilla_version[1]);
            self::set('USR_BROWSER_AGENT', 'MOZILLA');
        } else {
            self::set('USR_BROWSER_VER', 0);
            self::set('USR_BROWSER_AGENT', 'OTHER');
        }
    }
	
	public static function set($setting, $value) {
        define($setting, $value);
    }
}