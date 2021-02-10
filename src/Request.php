<?php

namespace Stilmark\Parse;

class Request
{
	private $vars;
	public static $serverVars = [
		'useragent' => 'HTTP_USER_AGENT',
		'referer' => 'HTTP_REFERER',
		'method' => 'REQUEST_METHOD',
		'ip' => 'REMOTE_ADDR',
		'locale' => 'HTTP_ACCEPT_LANGUAGE',
		'country' => 'HTTP_CF_IPCOUNTRY',
	];

	function __construct()
	{
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		foreach(self::$serverVars AS $static => $serverVar) {
			$this->vars[$static] = isset($_SERVER[$serverVar]) ? $_SERVER[$serverVar]:null;
		}

		$this->vars = array_merge(parse_url(self::url()), $this->vars);
	}

	public static function init()
	{
		 return new self;
	}

	public static function get()
	{
		 $Request = self::init();
		 return $Request->vars;
	}

	public static function url()
	{
		return 'http' . (isset($_SERVER['HTTPS']) && isset($_SERVER['HTTPS']) ? 's':'') . '://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}

    public static function __callStatic($name, $arguments)
    {
        $Request = self::init();
        if (isset($Request->vars[$name])) {
        	return $Request->vars[$name];
        } else {
        	return false;
        }
    }

}