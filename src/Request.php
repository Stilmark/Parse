<?php

namespace Stilmark\Parse;

// Inspiration
// https://github.com/tochix/shapes-api/blob/master/src/Service/Http/Request.php

class Request
{
	public $vars;
	public static $serverVars = [
		'useragent' => 'HTTP_USER_AGENT',
		'referer' => 'HTTP_REFERER',
		'method' => 'REQUEST_METHOD',
		'uri' => 'REQUEST_URI',
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

		if ($_POST) {
			$this->vars['post'] = filter_var_array($_POST, FILTER_SANITIZE_STRING);
		}

		if ($_GET) {
			$this->vars['get'] = filter_var_array($_GET, FILTER_SANITIZE_STRING);
		}

		$this->vars = array_merge(parse_url(self::url()), $this->vars);
	}

	public static function all()
	{
		return new Request();
	}

	public static function global_var($global = 'post', $var = false)
	{
		$request = new self;
		if ($var) {
			return $request->vars[$global][$var] ?? false;
		}
		return $request->vars[$global] ?? false;
	}

	public static function get($var = false)
	{
		return self::global_var('get', $var);
	}

	public static function post($var = false)
	{
		return self::global_var('post', $var);
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