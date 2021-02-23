<?php

namespace Stilmark\Parse;

class Request
{
	public $vars;
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

	public static function get()
	{
		return new Request();
	}

	public static function url()
	{
		return 'http' . (isset($_SERVER['HTTPS']) && isset($_SERVER['HTTPS']) ? 's':'') . '://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}

    public static function __callStatic($name, $arguments)
    {
        $request = new self;
        if (isset($request->vars[$name])) {
        	return $request->vars[$name];
        } else {
        	return false;
        }
    }

    public function __toString()
	{
        return json_encode($this->vars);
    }

}