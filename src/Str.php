<?php

namespace Stilmark\Parse;

class Str
{

	public $str;

    public static function make($str = '')
    {
        return new Str( $str );
    }

    public function __construct($str = '')
    {
        $this->str = mb_convert_encoding($str, 'UTF-8', 'auto');
    }

    // Case manipulators

	function upper()
	{
		$this->str = mb_strtoupper($this->str);
		return $this;
	}

	function lower()
	{
		$this->str = mb_strtolower($this->str);
		return $this;
	}

	function capitalize()
	{
		$this->str = mb_strtoupper(mb_substr($this->str, 0, 1)) . mb_substr($this->str, 1);;
		return $this;
	}

	function title()
	{
		$this->str = mb_convert_case($this->str, MB_CASE_TITLE, "UTF-8");
		return $this;
	}

	function camel() {
		$firstChar = Str::make($this->str)->slice(0,1)->lower();
		$this->title()->slice(1)->prepend($firstChar);
		$this->str = preg_replace('/[^\da-z]/i', '', $this->str);
		return $this;
	}

	// Content modifiers

	function trim($chars = '')
	{
		$this->str = trim($this->str, $chars);
		return $this;
	}

	function trimLeft($chars = '')
	{
		$this->str = ltrim($this->str, $chars);
		return $this;
	}

	function trimRight($chars = '')
	{
		$this->str = rtrim($this->str, $chars);
		return $this;
	}

	function trimInside()
	{
		$this->str = preg_replace('/\s+/', ' ', $this->str);
		return $this;
	}

	function replace($find, $replace)
	{
		$this->str = str_replace($find, $replace, $this->str);
		return $this;
	}

	function slice($start = 0, $length = null)
	{
		$this->str = mb_substr($this->str, $start, $length);
		return $this;
	}

	function prepend($str)
	{
		$this->str = $str . $this->str;
		return $this;
	}

	function append($str)
	{
		$this->str = $this->str . $str;
		return $this;
	}

	function wrap($left, $right)
	{
		$this->str = $left . $this->str . $right;
		return $this;
	}

	function wrapTag($tag, $attr = [])
	{
		$attributes = [];
		if (count($attr) > 0) {
			$attributes[] = '';
			foreach($attr AS $key => $value) {
				$attributes[] = sprintf('%s="%s"', $key, $value);
			}
		}
		return $this->wrap('<'. $tag . implode(' ', $attributes) .'>','</'.$tag.'>');
	}

	function repeat($str, $count = 0)
	{
		$this->str = str_repeat($str, $count);
	}

	function pad($str, $max_length)
	{
		$this->str = str_pad($this->str, $max_length, $str, STR_PAD_BOTH);
	}

	function padLeft($str, $max_length)
	{
		$this->str = str_pad($this->str, $max_length, $str, STR_PAD_LEFT);
	}

	function padRight($str, $max_length)
	{
		$this->str = str_pad($this->str, $max_length, $str);
	}


	// String properties

	function length()
	{
		return mb_strlen($this->str);
	}

	function before($str)
	{
		return mb_substr($this->str, 0, mb_strpos($this->str, $str));
	}

	function after($str) {
		// todo
	}

	function truncate($length, $append)
	{
		$str =  $this->slice(0, $length);
		if (!empty($append)) {
			$str->append($append);
		}
		return $str;
	}

	function split($token)
	{
		return explode($token, $this->str);
	}

	function join($array)
	{
		return implode($this->str, $array);
	}

	function isEmpty()
	{
		return empty($this->str) ? true:false;
	}

	function is($str)
	{
		return ($this->str == $str) ? true:false;
	}

	function contains($str)
	{
		// todo
	}

	function beginsWith($str, $case_sensitive = true)
	{
		if ($case_sensitive) {
			return (mb_strpos($this->str, $str) === 0) ? true:false;
		} else {
			return (mb_stripos($this->str, $str) === 0) ? true:false;
		}
	}

    public function __toString()
    {
        return $this->str;
    }

}