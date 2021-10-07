<?php

namespace Stilmark\Parse;

class Request
{
	public static $serverVars = [
		'useragent' => 'HTTP_USER_AGENT',
		'referer' => 'HTTP_REFERER',
		'method' => 'REQUEST_METHOD',
		'uri' => 'REQUEST_URI',
		'ip' => 'REMOTE_ADDR',
        'host' => 'SERVER_NAME',
		'locale' => 'HTTP_ACCEPT_LANGUAGE',
		'country' => 'HTTP_CF_IPCOUNTRY',
        'time' => 'REQUEST_TIME',
        'lang' => 'LANGUAGE'
	];
	public static $globalsVars = [
	    'post', 'get'
    ];

	function __construct()
	{
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
	}

	public static function global(String $globalKey = '_GET', Array $vars = [])
	{
	    $globalVar = $GLOBALS[$globalKey];

        if (count($vars)) {
            if (count($vars) == 1) {
                return $globalVar[self::$serverVars[current($vars)]] ?? false;
            } else {
                return array_intersect_key($globalVar, array_flip($vars)) ?? [];
            }
        } else {
            return $globalVar ?? false;
        }

	}

    public static function __callStatic($name, $arguments)
    {
        if (isset(self::$serverVars[$name])) {
            return self::global('_SERVER', [$name]);
        } elseif (in_array($name, self::$globalsVars)) {
            return self::global('_'.strtoupper($name), $arguments);
        } else {
        	return false;
        }
    }

    public function __toString()
	{
        return json_encode($this);
    }

}