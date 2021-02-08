<?php

namespace Stilmark\Parse;

class Str
{

	public $str;

    public static function make($str)
    {
        return new Str($str);
    }

    public function __construct($str)
    {
        $this->str = $str;
    }

    // Manipulators : Case

	function upper()
	{
		$this->str = strtoupper($this->str);
		return $this;
	}

	function lower()
	{
		$this->str = strtolower($this->str);
		return $this;
	}

	function capitalize()
	{
		$this->str = ucfirst($this->str);
		return $this;
	}

	function title()
	{
		$this->str = ucwords($this->str);
		return $this;
	}

	function camel() {
		$this->title();
		$this->str = preg_replace('/[^\da-z]/i', '', $this->str);
		return $this;
	}

	// Manipulators : alterations

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

	function replace($find, $replace)
	{
		$this->str = str_replace($find, $replace, $this->str);
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

	// Properties

	function length()
	{
		return strlen($this->str);
	}

	function before($str)
	{
		return substr($this->str, 0, strpos($this->str, $str));
	}

	function after($str) {
		// todo
	}

	function isEmpty()
	{
		return empty($this->str) ? true:false;
	}

    public function __toString()
    {
        return $this->str;
    }

}