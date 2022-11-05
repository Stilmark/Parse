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
        'lang' => 'LANGUAGE',
        'args' => 'URI_ARGUMENTS'
	];
	public static $globalsVars = [
	    'post', 'get', 'cookie'
    ];

	function __construct()
	{
	    // Cloudflare variables override
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }
	}

	public static function global( String $globalKey = '_GET', Array $vars = [] )
	{
	    $globalVar = $GLOBALS[$globalKey];

        if (count($vars)) {
            if (count($vars) == 1) {
                return $globalVar[current($vars)] ?? false;
            } else {
                return array_intersect_key($globalVar, array_flip($vars)) ?? [];
            }
        } else {
            return $globalVar ?? false;
        }
	}

    public static function __callStatic( String $name, Array $arguments)
    {
        // Check if arguments are multidimensional
        if (count($arguments) != count($arguments, COUNT_RECURSIVE)) {
            $arguments = current($arguments);
        }

        if (isset(self::$serverVars[$name])) {
            if (count($arguments)) {
                return self::global(self::$serverVars[$name], $arguments);
            } else {
                return self::global('_SERVER', [self::$serverVars[$name]]);
            }

        } elseif (in_array($name, self::$globalsVars)) {
            return self::global('_'.strtoupper($name), $arguments);
        } elseif ($name == 'url') {
            return 'http'.($_SERVER['HTTPS'] == 'on' ? 's':'').'://'.self::host().self::uri();
        } elseif ($name == 'path') {
            $url = parse_url(self::url());
            return $url['path'];
        } else {
        	return false;
        }
    }

    public function __toString()
	{
        return json_encode($this);
    }

}